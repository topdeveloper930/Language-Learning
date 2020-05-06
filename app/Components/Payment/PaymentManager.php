<?php


namespace App\Components\Payment;


use App\Components\Payment\Classes\PaymentGateway;
use App\Components\Payment\Drivers\Check;
use App\Components\Payment\Drivers\Giftcard;
use App\Components\Payment\Drivers\Manual;
use App\Components\Payment\Drivers\Paypal;
use App\Components\Payment\Drivers\Stripe;
use App\Components\Payment\Drivers\Transfer;
use App\Student;
use Illuminate\Support\Manager;

class PaymentManager extends Manager {

	/**
	 * Get a driver instance.
	 *
	 * @param  string|null  $name
	 * @return mixed
	 */
	public function gateway( $name = null )
	{
		return $this->driver( PaymentGateway::driver( $name ) );
	}

	/**
	 * @inheritDoc
	 */
	public function getDefaultDriver()
	{
		return $this->app['config']['payment.default'];
	}

	public function createStripeDriver()
	{
		return new Stripe(
			$this->getUser(),
			request(),
			array_merge(
				config('payment' ),
				config('services.stripe' )
			)
		);
	}

	public function createPaypalDriver()
	{
		return new Paypal(
			$this->getUser(),
			request(),
			array_merge(
				config('payment' ),
				config('services.paypal' )
			)
		);
	}

	public function createCheckDriver()
	{
		return new Check(
			$this->getUser(),
			request(),
			config('payment' )
		);
	}

	public function createTransferDriver()
	{
		return new Transfer(
			$this->getUser(),
			request(),
			config('payment' )
		);
	}

	public function createGiftcardDriver()
	{
		return new Giftcard(
			$this->getUser(),
			request(),
			config('payment' )
		);
	}

	public function createManualDriver()
	{
		return new Manual(
			factory( Student::class )->make(),
			request(),
			config('payment' )
		);
	}

	public function getUser()
	{
		return ( auth()->user() )
			? auth()->user()->getInstance()
			: auth()->user();
	}
}