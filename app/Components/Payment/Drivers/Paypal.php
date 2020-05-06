<?php


namespace App\Components\Payment\Drivers;


use App\Components\Payment\Classes\PaymentGateway;
use App\Components\Payment\Classes\PaymentType;
use App\Components\Payment\Classes\Paypal\PaypalConfig;
use App\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalHttp\HttpException;
use PayPalHttp\HttpResponse;

class Paypal extends Driver {

	const STATUS_COMPLETED  = 'COMPLETED';
	const STATUS_APPROVED   = 'APPROVED';
	const STATUS_VOIDED     = 'VOIDED';

	protected $paymentGateway = PaymentGateway::PAYPAL;

	protected $paymentType = PaymentType::ONLINE;

	/**
	 * @inheritDoc
	 */
	public function send()
	{
		parent::send();

		$order = $this->createOrder( $this->transactionID );

		$this->transaction->gatewayTransID = $order->id;
		$this->transaction->save();

		$this->purchase->gateway_reference = $order->id;
		$this->purchase->result = $order;
		$this->purchase->save();

		return [ 'redirectUrl' => $this->getUrl( $order->links, 'approve' )];
	}

	public function webhook()
	{
		$payeeEmail = $this->request->json('resource.purchase_units.0.payee.email_address');
		$transactionID = $this->request->json('resource.purchase_units.0.reference_id');
		$eventType = $this->request->json('event_type');
		$this->transaction =  Transaction::find( $transactionID );

		abort_unless(
			$payeeEmail  AND $transactionID AND $this->transaction
                AND (PaypalConfig::CHECKOUT_ORDER_APPROVED == $eventType OR PaypalConfig::CHECKOUT_ORDER_VOIDED == $eventType ),
			422
		);

		if( Transaction::COMPLETED == $this->transaction->paymentStatus ) return;

		$credentials = $this->getCredentialsForMerchant( $payeeEmail );

		$apiContext = PaypalConfig::getApiContext( $credentials['client_id'], $credentials['secret'] );

		abort_unless( PaypalConfig::verifyWebhookEvent( $apiContext, $credentials['webhook_id'] ), 422 );

		$this->instantCheckout();
	}

	protected function receiverEmail()
	{
		return $this->getCredentials()[ 'merchant' ];
	}

	protected function setTransactionCompleted()
	{
		$this->transaction->paymentFee = $this->getPaymentFee();
		$this->transaction->emailUsed = $this->paymentInfo->payer->email_address;

		parent::setTransactionCompleted();
	}

	/**
	 * Pre-calculate fee for US as a fallback option.
	 * The real fee to be extracted from the final capture object in the response.
	 *
	 * @return float|int
	 */
	protected function getPaymentFee()
	{
		if( $this->paymentInfo ) {
			$capture = Arr::first(
				$this->paymentInfo->purchase_units[0]->payments->captures,
				function ( $capture ){ return $capture->final_capture; }
			);

			if( $capture AND property_exists($capture, 'seller_receivable_breakdown' ))
				return $capture->seller_receivable_breakdown->paypal_fee->value;
		}

		return round( $this->config['fee_percent'] * $this->balance['total'] + $this->config['fee_fixed'], 2 );
	}

	public function retrieveData()
	{
		parent::retrieveData();

		if( !$this->paymentInfo ) {
			$response = $this->getOrder();
			$this->paymentInfo = $response->result;
		}
	}

	protected function getAction()
	{
		$response = $this->captureOrGetOrder();

		$this->paymentInfo = $response->result;

		if( $response->statusCode >= 500 ) {
			abort( $response->statusCode );
		}

		if( static::STATUS_COMPLETED == $response->result->status ){
			return 'confirm';
		}
		elseif ( static::STATUS_VOIDED == $response->result->status ) {
			$this->error = 'purchase_result.paypal.voided';
			return 'cancel';
		}

		return parent::getAction();
	}

	protected function getReasonOfDenial()
	{
		return $this->error;
	}

	private function createOrder( $transactionID )
	{
		$request = new OrdersCreateRequest();

		$request->prefer('return=representation');

		$request->body = [
			"intent"         => "CAPTURE",
			"purchase_units" => [[
				'reference_id' => $transactionID,
				'description' => sprintf( $this->config['description_tplt'], $this->courseType ),
				'amount'       => [
					"value"         => $this->units( $this->balance['total'] ),
					"currency_code" => $this->config['currency_code']
				]
			]],
			"application_context" => [
				'brand_name' => config( 'app.name' ),
				'shipping_preference' => 'NO_SHIPPING',
				'cancel_url' => route( 'students', [
					'controller' => 'purchase-result',
					'id'         => $transactionID
				]),
				'return_url' => route( 'students', [
					'controller' => 'purchase-result',
					'id'         => $transactionID
				])
			]
		];

		try {
			$response = $this->client()->execute( $request );
		}
		catch ( HttpException $ex ) {
			Log::error('Paypal create order request: ' . $ex->getMessage(), $ex->getTrace());
			abort( 422, __( 'student_purchase.service_unavailable' ) );
		}

		// We can get the deserialized version from the result attribute of the response
		return $response->result;
	}

	private function captureOrGetOrder( $status = null )
	{
		$response = $this->getOrder();

		if( 200 == $response->statusCode AND static::STATUS_APPROVED == $response->result->status )
			return $this->captureOrder();

		return $response;
	}

	private function getOrder()
	{
		$request = new OrdersGetRequest( $this->transaction->gatewayTransID );

		try {
			return $this->client()->execute( $request );
		}
		catch ( HttpException $e ) {
			$body = json_decode($e->getMessage());
			$body->status = static::STATUS_VOIDED;

			return new HttpResponse($e->statusCode, $body, [] );
		}
	}

	private function captureOrder()
	{
		$request = new OrdersCaptureRequest( $this->transaction->gatewayTransID );
		$request->prefer('return=representation');

		try {
			return $this->client()->execute( $request );
		}
		catch ( HttpException $e ) {
			Log::error( 'PAYPAL::captureOrder(): ' . $e->getMessage(), $e->getTrace() );

			abort( $e->statusCode, $e->getMessage() );
		}
	}

	private function client()
	{
		return new PayPalHttpClient( $this->environment() );
	}

	/**
	 * Set up and return PayPal PHP SDK environment with PayPal access credentials.
	 *
	 * @return mixed
	 */
	private function environment()
	{
		$credentials = $this->getCredentials();

		$clientEnv = ( app()->environment( 'production' ) )
			? \PayPalCheckoutSdk\Core\ProductionEnvironment::class
			: \PayPalCheckoutSdk\Core\SandboxEnvironment::class;

		return new $clientEnv( $credentials[ 'client_id' ], $credentials[ 'secret' ] );
	}

	private function getUrl( $links, $rel )
	{
		return Arr::first(
			$links,
			function ($link) use($rel) { return $rel == $link->rel; }
		)->href;
	}

	/**
	 * The credentials depend on the course language - separate merchant for Spanish students.
	 *
	 * @return mixed
	 */
	private function getCredentials( $language = null )
	{
		$language OR $language = strtolower( $this->getCourse()->language );

		return ( app()->environment( 'production' ) AND array_key_exists( $language, $this->config ) )
			? $this->config[ 'credentials' ][ $language ]
			: $this->config[ 'credentials' ][ 'default' ];
	}

	private function getCredentialsForMerchant( $email )
	{
		return ( app()->environment( 'production' ) )
			? Arr::first( $this->config[ 'credentials' ], function( $creds ) use( $email ) { return $email == $creds['merchant']; } )
			: $this->config[ 'credentials' ][ 'default' ];
	}

	public function getContext( $language )
	{
		$credentials = $this->getCredentials( $language );
		return PaypalConfig::getApiContext( $credentials[ 'client_id' ], $credentials[ 'secret' ] );
	}
}