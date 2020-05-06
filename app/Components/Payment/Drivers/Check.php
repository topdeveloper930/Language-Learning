<?php


namespace App\Components\Payment\Drivers;


use App\Components\Payment\Classes\PaymentGateway;
use App\Notifications\PaymentInstruction;
use Illuminate\Support\Arr;

class Check extends Driver {

	protected $paymentGateway = PaymentGateway::CHECK;

	protected function afterSend()
	{
		parent::afterSend();

		$this->clearSession();

		$this->user->notify( new PaymentInstruction( $this->transaction ));
	}

	protected function getPaymentFee()
	{
		return round(
			$this->balance['total'] * Arr::get( $this->config['check'], 'payment_fee', 3 ) / 100,
			2
		);
	}
}