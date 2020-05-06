<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

	'mailgun' => [
		'domain' => env( 'MAILGUN_DOMAIN' ),
		'secret' => env( 'MAILGUN_SECRET' ),
	],

	'mandrill' => [
		'secret' => env( 'MANDRILL_KEY' ),
	],

	'ses' => [
		'key'    => env( 'SES_KEY' ),
		'secret' => env( 'SES_SECRET' ),
		'region' => 'us-east-1',
	],

	'sparkpost' => [
		'secret' => env( 'SPARKPOST_SECRET' ),
	],

	'stripe' => [
		'model'                => App\Student::class,
		'key'                  => env( 'STRIPE_KEY' ),
		'secret'               => env( 'STRIPE_SECRET' ),
		'signingSecret'        => env( 'STRIPE_SIGNING_SECRET' ),
		'payment_method_types' => [ 'card' ],
		'currency'             => 'usd',
		'min_amount'           => .5,
		'units'                => 100,
		'sessions_expire'      => 1, // days (Checkout Sessions expire 24 hours after creation.)
		'fee_percent'          => .029,
		'fee_fixed'            => .3,
	],

	'paypal' => [
		'credentials' => [
			'default' => [
				'client_id'  => env( 'PAYPAL_CLIENT_ID', '' ),
				'secret'     => env( 'PAYPAL_SECRET', '' ),
				'merchant'   => env( 'LLGBL_MERCHANT_ID', '' ),
				'webhook_id' => env( 'PAYPAL_WEBHOOK_ID', '' )
			],
			'spanish' => [
				'client_id'  => env( 'SPANISH_PAYPAL_CLIENT_ID', '' ),
				'secret'     => env( 'SPANISH_PAYPAL_SECRET', '' ),
				'merchant'   => env( 'LLGBL_MERCHANT_ID_MEX', '' ),
				'webhook_id' => env( 'SPANISH_PAYPAL_WEBHOOK_ID', '' )
			]
		],
		'fee_percent'   => .029,
		'fee_fixed'     => .3,
		'currency_code' => 'USD'
	],

	'facebook' => [
		'sdk_version'           => env( 'FB_API_SDK_VERSION' ),
		'client_id'             => env( 'FB_API_ID' ),
		'client_secret'         => env( 'FB_API_SECRET' ),
		'default_graph_version' => env( 'FB_API_DEFAULT_GRAPH_VERSION' ),
		'redirect'              => env( 'FB_REDIRECT_URL' ),
	],

	'google' => [
		'client_id'      => env( 'GOOGLE_CLIENT_ID' ),
		'client_secret'  => env( 'GOOGLE_CLIENT_SECRET' ),
		'api_key'        => env( 'GOOGLE_API_KEY' ),
		'redirect'       => env( 'GOOGLE_REDIRECT_URL' ),
		'tag_manager_id' => env( 'GOOGLE_TAG_MANAGER_ID' )
	]
];
