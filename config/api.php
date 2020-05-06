<?php

return [
	'transaction'   => [
		'gateway'       => 'API credit',
		'paymentFee'    => 0,
		'receiverEmail' => env( 'LLGBL_MERCHANT_ID', 'info@livelingua.com' )
	],
	'gcalendar' => [
		'clientID'  => env( 'GAPI_CLIENT_ID' ),
		'apiKey'    => env( 'GAPI_API_KEY' ),
		'location'  => env( 'GAPI_LOCATION', 'LiveLingua' ),
		'batch_max_requests' => config( 'google.batch_max_requests', 50 ),
		'student_class_pref' => 'LiveLingua Class'
	],
	'gender_api_key' => env( 'GENDER_API_KEY' ), // https://gender-api.com
	'activecampaign' => [
		'url' => env( 'ACTIVE_CAMPAIGN_URL' ),
		'key' => env( 'ACTIVE_CAMPAIGN_KEY' )
	]
];