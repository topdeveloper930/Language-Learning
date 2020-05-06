<?php


namespace App\Components\Payment\Drivers;

use App\Components\Payment\Classes\PaymentGateway;
use App\Components\Payment\Classes\PaymentType;
use App\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Stripe\Event;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Webhook;

class Stripe extends Driver {

	const SIGNATURE_HEADER = 'stripe-signature';

	protected $paymentGateway = PaymentGateway::STRIPE;

	protected $paymentType = PaymentType::ONLINE;

	/**
	 * @inheritDoc
	 */
	public function send()
	{
		parent::send();

		$this->setStripe();

		try {
			$session = $this->createSession( $this->transaction->getKey() );
		}
		catch ( ApiErrorException $e ) {
			abort( 422, __( 'student_purchase.service_unavailable' ) );
		}

		$this->transaction->gatewayTransID = $session->payment_intent;
		$this->transaction->save();

		$this->purchase->gateway_reference = $session->id;
		$this->purchase->save();

		return [ 'sessionId' => $session->id ];
	}

	public function webhook()
	{
		$this->setStripe();

		$event = Webhook::constructEvent(
			@file_get_contents('php://input'),
			$this->request->header(static::SIGNATURE_HEADER ),
			$this->config[ 'signingSecret' ]
		);

		if ( Event::PAYMENT_INTENT_SUCCEEDED != $event->type ) return;

		$this->paymentInfo = $event->data->object;
		$this->transaction = Transaction::where( 'gatewayTransID', $this->paymentInfo->id )->first();

		if( Transaction::PENDING != $this->transaction->paymentStatus ) return;

		$this->retrieveData();

		$this->confirm();
	}

	public function units( $amount = null )
	{
		return round(
			parent::units( $amount )
		);
	}

	public function retrieveData()
	{
		parent::retrieveData();

		if( !$this->paymentInfo )
			$this->retrievePaymentIntent();
	}

	protected function getPaymentFee()
	{
		return round(
			$this->balance['total'] * Arr::get( $this->config, 'fee_percent' )
								+ Arr::get( $this->config, 'fee_fixed' ),
			2
		);
	}

	protected function getAction()
	{
		$this->retrievePaymentIntent();

		if( PaymentIntent::STATUS_SUCCEEDED == $this->getPaymentStatus() ){
			return 'confirm';
		}
		elseif ( PaymentIntent::STATUS_CANCELED == $this->getPaymentStatus() ){
			return 'cancel';
		}
		elseif ( $this->isCancelable( $this->getPaymentStatus(), $this->paymentInfo->created ) ) {
			try {
				$this->paymentInfo = $this->paymentInfo->cancel([
					'cancellation_reason' => 'abandoned'
				]);

				if( PaymentIntent::STATUS_CANCELED == $this->getPaymentStatus() )
					return 'cancel';
			}
			catch ( \Stripe\Exception\InvalidRequestException $e ) {}
		}

		$this->transaction->purchase->result = $this->paymentInfo;
		$this->transaction->purchase->save();

		return parent::getAction();
	}

	protected function getReasonOfDenial()
	{
		return ( $this->paymentInfo->last_payment_error )
			? $this->paymentInfo->last_payment_error->message
			: 'purchase_result.stripe.' . $this->paymentInfo->cancellation_reason;
	}

	public function setStripe()
	{
		\Stripe\Stripe::setApiKey( $this->config[ 'secret' ] );
		\Stripe\Stripe::setLogger( Log::getMonolog()->withName( $this->paymentGateway ) );
	}

	/**
	 * @return \Stripe\Checkout\Session
	 * @throws \Stripe\Exception\ApiErrorException
	 */
	private function createSession( $transactionID )
	{
		return \Stripe\Checkout\Session::create([
			'customer_email'       => $this->user->email,
			'payment_method_types' => $this->config['payment_method_types'],
			'client_reference_id'  => $transactionID,
			'line_items'           => [[
				'name'        => $this->courseType,
				'description' => sprintf( $this->config['description_tplt'], $this->courseType ),
				'amount'      => $this->units( $this->balance['total'] ),
				'currency'    => $this->config['currency'],
				'quantity'    => 1,
			]],
			'success_url' => route( 'students', [
					'controller' => 'purchase-result',
					'id'         => $transactionID
				] ),
			'cancel_url' => route( 'students', [
					'controller' => 'purchase-result',
					'id'         => $transactionID
				] )
		]);
	}

	private function getPaymentStatus()
	{
		return $this->paymentInfo->status;
	}

	private function isCancelable( $status, $created )
	{
		return in_array( $status, [
			PaymentIntent::STATUS_REQUIRES_PAYMENT_METHOD,  // Payment failed
			PaymentIntent::STATUS_REQUIRES_ACTION           // Authenticating with 3D Secure failed
		]) AND time() > $created + $this->config[ 'sessions_expire' ] * 24 * 3600;
	}

	private function retrievePaymentIntent()
	{
		$this->setStripe();

		try {
			$this->paymentInfo = PaymentIntent::retrieve( $this->transaction->gatewayTransID );
		}
		catch ( ApiErrorException $e ) {
			abort( 400 );
		}
	}

	protected function performServiceCancellation()
	{
		parent::performServiceCancellation();

		if( in_array( $this->paymentInfo->status, [
			PaymentIntent::STATUS_REQUIRES_PAYMENT_METHOD,  // Payment failed
			PaymentIntent::STATUS_REQUIRES_ACTION           // Authenticating with 3D Secure failed
		])) {
			$this->paymentInfo = $this->paymentInfo->cancel([
				'cancellation_reason' => 'requested_by_customer'
			]);
		}
	}
}