<?php


namespace App\Components\Payment\Classes;


class PaymentGateway {
	const CREDIT_CARD   = 'Credit Card';
	const STRIPE        = 'Stripe';
	const PAYPAL        = 'Paypal';
	const CHECK         = 'U.S. Check';
	const BANK_TRANSFER = 'Bank Transfer';
	const GIFT_CARD     = 'Gift Card';
	const REFUND        = 'Refund';
	const MANUAL        = 'Manual';

	const GW_STRIPE     = 'stripe';
	const GW_PAYPAL     = 'paypal';
	const GW_CHECK      = 'check';
	const GW_TRANSFER   = 'transfer';
	const GW_GIFT_CARD  = 'giftcard';
	const GW_MANUAL     = 'manual';

	public static function driver( $gateway )
	{
		switch ( $gateway ) {
			case static::PAYPAL:
				return static::GW_PAYPAL;
			case static::STRIPE:
			case static::CREDIT_CARD:
				return static::GW_STRIPE;
			case static::GIFT_CARD:
				return static::GW_GIFT_CARD;
			case static::BANK_TRANSFER:
				return static::GW_TRANSFER;
			case static::CHECK:
				return static::GW_CHECK;
			case static::MANUAL:
				return static::GW_MANUAL;
			default:
				return $gateway;
		}
	}

	public static function isInstant( $gateway )
	{
		return $gateway == static::GW_PAYPAL
			OR $gateway == static::PAYPAL
			OR $gateway == static::STRIPE
			OR $gateway == static::GW_STRIPE
			OR $gateway == static::CREDIT_CARD;
	}
}