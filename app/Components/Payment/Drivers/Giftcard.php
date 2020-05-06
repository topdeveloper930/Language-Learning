<?php


namespace App\Components\Payment\Drivers;


use App\Components\Payment\Classes\PaymentGateway;
use App\Components\Payment\Classes\PaymentType;
use App\Transaction;

class Giftcard extends Driver {

	protected $paymentGateway = PaymentGateway::GIFT_CARD;

	protected $paymentType = PaymentType::GIFT_CARD;


	/**
	 * @inheritDoc
	 */
	public function send()
	{
		$retVal = parent::send();

		// The parameter here is just to comply with the parent method.
		// The transaction and the container are still available.
		$this->confirm( $this->transaction );

		return $retVal;
	}

	protected function afterSend()
	{
		parent::afterSend();

		$this->clearSession();
	}

	protected function getGatewayTransID()
	{
		return $this->offsetGet( 'giftcard_code' );
	}

	protected function getPaymentAmount()
	{
		return $this->balance['giftcard'];
	}

	protected function getPaymentFee()
	{
		$gc = \App\GiftCard::where( 'code', $this->offsetGet( 'giftcard_code' ) )->first();

		$gc_transaction = Transaction::where([
			['gatewayTransID', $this->offsetGet( 'giftcard_code' )],
			['paymentFor', $this->offsetGet( 'Gift Card' )],
			['paymentStatus', Transaction::COMPLETED]
		])->first();

		return ( $gc_transaction )
			? round( $this->balance[ 'giftcard' ] / $gc->amount * $gc_transaction->paymentFee, 2 )
			: round( $this->balance[ 'giftcard' ] / $gc->amount * ( $gc->amount * .029 + .3 ), 2 );
	}

	/**
	 * No need to obtain data from cache.
	 *
	 * @param string|null $key
	 */
	public function getDataFromCache( $key = null ) {}
}