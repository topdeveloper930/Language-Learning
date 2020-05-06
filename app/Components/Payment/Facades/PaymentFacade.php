<?php


namespace App\Components\Payment\Facades;


use Illuminate\Support\Facades\Facade;

class PaymentFacade extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'Payment';
	}
}