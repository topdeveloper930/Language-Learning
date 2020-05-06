<?php


namespace App\Components\Payment\Classes\Paypal;

use PayPal\Api\VerifyWebhookSignature;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalConfig {

	const LEVEL_DEBUG   = 'DEBUG';
	const LEVEL_INFO    = 'INFO';
	const LEVEL_WARNING = 'WARNING';
	const LEVEL_ERROR   = 'ERROR';

	const VERIFICATION_SUCCESS = 'SUCCESS';
	const VERIFICATION_FAILURE = 'FAILURE';

	const MODE_LIVE     = 'live';
	const MODE_SANDBOX  = 'sandbox';

	const CHECKOUT_ORDER_APPROVED   = 'CHECKOUT.ORDER.APPROVED';
	const CHECKOUT_ORDER_VOIDED     = 'CHECKOUT.ORDER.VOIDED';

	/**
	 * Helper method for getting an APIContext for all calls
	 * @param string $clientId Client ID
	 * @param string $clientSecret Client Secret
	 * @return ApiContext
	 */
	public static function getApiContext( $clientId, $clientSecret )
	{
		// ### Api context
		// Use an ApiContext object to authenticate
		// API calls. The clientId and clientSecret for the
		// OAuthTokenCredential class can be retrieved from
		// developer.paypal.com

		$apiContext = new ApiContext(
			new OAuthTokenCredential(
				$clientId,
				$clientSecret
			)
		);

		// Comment this line out and uncomment the PP_CONFIG_PATH
		// 'define' block if you want to use static file
		// based configuration

		$apiContext->setConfig([
			'mode'           => ((app()->environment( 'production' )) ? static::MODE_LIVE : static::MODE_SANDBOX ),
			'log.LogEnabled' => true,
			'log.FileName'   => storage_path('logs/paypal-' . date('Y-m-d' ) . '.log'),
			'log.LogLevel'   => ((app()->environment( 'production' )) ? static::LEVEL_INFO : static::LEVEL_DEBUG ),
			'cache.enabled'  => true,
			'cache.FileName' => storage_path( 'framework/cache/data/paypal' ), // for determining paypal cache directory
			// 'http.CURLOPT_CONNECTTIMEOUT' => 30
			// 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
			//'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
		]);

		return $apiContext;
	}

	public static function verifyWebhookEvent( $apiContext, $webhookId )
	{
		$headers = getallheaders();

		/**
		 * In documentations https://developer.paypal.com/docs/api/webhooks/v1/#verify-webhook-signature
		 * All header keys as UPPERCASE
		 */
		$headers = array_change_key_case($headers, CASE_UPPER);

		$requestBody = @file_get_contents('php://input');

		$signatureVerification = new VerifyWebhookSignature();

		$signatureVerification->setAuthAlgo( $headers['PAYPAL-AUTH-ALGO'] );
		$signatureVerification->setTransmissionId( $headers['PAYPAL-TRANSMISSION-ID'] );
		$signatureVerification->setCertUrl( $headers['PAYPAL-CERT-URL'] );
		// Note that the Webhook ID must be a currently valid Webhook that you created with your client ID/secret.
		$signatureVerification->setWebhookId( $webhookId );
		$signatureVerification->setTransmissionSig( $headers['PAYPAL-TRANSMISSION-SIG'] );
		$signatureVerification->setTransmissionTime( $headers['PAYPAL-TRANSMISSION-TIME'] );

		$signatureVerification->setRequestBody( $requestBody );

		try {
			/** @var \PayPal\Api\VerifyWebhookSignatureResponse $output */
			$output = $signatureVerification->post( $apiContext );
		}
		catch ( \Exception $ex ) {
			abort( 422 );
		}

		return static::VERIFICATION_SUCCESS == $output->getVerificationStatus();
	}
}