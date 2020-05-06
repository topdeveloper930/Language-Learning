<?php

namespace App\Http\Controllers\Page;

use \App\Http\Controllers\PageController;

use App\Components\Payment\Classes\PaymentGateway;
use App\Http\Controllers\StudentController;
use App\Transaction;
use App\TrialClassLog;
use Illuminate\Support\Facades\Cookie;
use Payment;


class GiftCardResultPageController extends PageController {

    protected $translation = 'gift_card_result.js';

    protected function make()
    {
	    parent::make();

	    // Transaction instance is vital - 404, if not found.
	    $transaction = Transaction::findOrFail( Arr::get( $this->arguments, 0 ) );

	    $this->processPayment( $transaction );

	    $this->setParams([
		    'page_title'  => __( 'purchase_result.purchase_result' ),
		    'transaction' => $transaction,
		    'nonInstantPay' => ( strcasecmp(PaymentGateway::BANK_TRANSFER, $transaction->paymentGateway ) == 0 OR strcasecmp(PaymentGateway::CHECK, $transaction->paymentGateway ) == 0 ),
		    'idOrUrl' => $this->getSessionIdOrRedirectURL( $transaction )
	    ]);
    }

    protected function obtainData()
    {
	    parent::obtainData();

	    abort_unless( Request::METHOD_DELETE == request()->method(), 404 );

        $this->cancelTransaction();
    }

    private function processPayment( $transaction )
    {
	    if( Transaction::PENDING == $transaction->paymentStatus ) {

		    if( PaymentGateway::isInstant( $transaction->paymentGateway ) ) {
			    Payment::gateway( $transaction->paymentGateway )
			           ->withTransaction( $transaction )
			           ->instantCheckout();
		    }
		    else {  // For clearing session either driver fits
			    Payment::gateway( 'transfer' )->clearSession();
		    }
	    }
    }

    private function getSessionIdOrRedirectURL( $transaction )
    {
    	switch ( $transaction->paymentGateway ) {
		    case PaymentGateway::STRIPE:
		    case PaymentGateway::CREDIT_CARD:
		    	return $transaction->purchase->gateway_reference;
		    case PaymentGateway::PAYPAL:
			    return $this->getPaypalApproveLink( $transaction->purchase );
	    }

	    return null;
    }


    private function getPaypalApproveLink( $response_result )
    {
    	if( property_exists( $response_result, 'result' ) AND is_array( $response_result->result )  )
		    return Arr::first( $response_result, function ($link) { return 'approve' == $link->rel; } )->href;

    	return '';
    }

	private function cancelTransaction()
	{
		Validator::make(
			$this->arguments,
			[ 0         => 'nullable|integer|exists:transactions,transactionID,paymentStatus,' . Transaction::PENDING ],
			[ 'exists'  => __( 'student_purchase.no_transaction' ) ]
		)->validate();

		$transaction = Transaction::find( $this->arguments[ 0 ] );

		Payment::gateway( $transaction->paymentGateway )
		       ->withTransaction( $transaction )
		       ->cancel( 'purchase_result.canceled_by_user' );
    }
    


}
