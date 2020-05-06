<?php

return [
	'methods' => [
		'stripe',
		'paypal',
		'check',
		'transfer'
	],
	'default'               => env( 'DEFAULT_PAYMENT_METHOD', \App\Components\Payment\Classes\PaymentGateway::STRIPE ),
	'check_out_to'          => 'Raymond C. Blakney',
	'check_address'         => 'Live Lingua' . PHP_EOL . 'c/o Raymond Blakney' . PHP_EOL . '24 Birchbrow Ave' . PHP_EOL . 'Weymouth, MA 02191' . PHP_EOL . 'USA',
	'wire_transfer_address' => '<b>Name:</b> Raymond C. Blakney
<b>Bank:</b> Key Bank
<b>Account #:</b> 12042005038
<b>ABA #:</b> 041001039
<b>SWIFT:</b> KEYBUS33
<b>Address:</b>
Civic Plaza, 4911 Euclid Ave.
Cleveland OH 44106-2244
United States of America.',
	'owner_company'          => 'Rayvensoft, Inc',
	'transfer_min_purchase'  => 10,
	'default_hours_purchase' => 10,
	'description_tplt'       => 'Charge for %s',
	'transfer' => [
		'payment_fee'     => 20, // USD, fixed
		'sessions_expire' => 30 // Days, storage term of the cache of the purchase data
	],
	'check' => [
		'payment_fee'     => 3, // %
		'sessions_expire' => 30 // Days, storage term of the cache of the purchase data
	]
];