<?php


namespace App\Components\Payment\Drivers;


use App\Components\Payment\Classes\PaymentGateway;

class Manual extends Driver {

	protected $paymentGateway = PaymentGateway::MANUAL;

}