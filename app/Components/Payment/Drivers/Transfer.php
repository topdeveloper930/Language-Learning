<?php


namespace App\Components\Payment\Drivers;


use App\Components\Payment\Classes\PaymentGateway;
use App\Notifications\PaymentInstruction;
use Illuminate\Support\Arr;

class Transfer extends Driver {

	protected $paymentGateway = PaymentGateway::BANK_TRANSFER;

	protected function afterSend()
	{
		parent::afterSend();

		$this->clearSession();

		$this->user->notify( new PaymentInstruction( $this->transaction ));
	}

	protected function getPaymentFee()
	{
		return Arr::get( $this->config['transfer'], 'payment_fee', 20 );
	}
}