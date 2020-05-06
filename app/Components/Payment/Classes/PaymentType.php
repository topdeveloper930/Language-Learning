<?php


namespace App\Components\Payment\Classes;


class PaymentType {
	const WEBHOOK       = 'webhook';
	const ONLINE        = 'online';
	const MANUAL        = 'manual entry';
	const API           = 'api';
	const GIFT_CARD     = 'gift card';
	const WEB_ACCEPT    = 'web_accept';
}