<?php

return [

    'globals'  => [
	    'businessName'  => 'Live Lingua',
	    'directoryName' => 'Schoolfinder',
	    'classCoordinatorName' => 'Mat',
	    'directorName'  => 'Ray',
	    'mainEmail'     => env( 'LLGBL_MAIN_EMAIL', 'info@livelingua.com' ),
	    'pageURL'       => '/schoolfinder/',
	    'sandbox'       => (int)( 'production' == strtolower( env( 'APP_ENV' ) ) ),
	    'rootPageHeaderLogo' => '/public/images/logo.svg', //'/img/logo-black-text.png',
	    'copyrightYear' => date( 'Y' ),
	    'mainAddress'   => "90 Canal Street, 4th Floor, Boston, MA, 02191, USA",
	    'emailMailingAddress' => '90 Canal Street, 4th Floor | Boston,MA | USA |',
	    'facebookURL'   => 'https://www.facebook.com/LiveLingua',
	    'twitterURL'    => 'https://twitter.com/livelingua',
	    'pinterestURL'  => 'https://www.pinterest.com/onmutu/',
	    'linkedinURL'   => 'https://www.linkedin.com/company/live-lingua',
	    'directorEmail' => 'ray@livelingua.com',
	    'noReplyEmail'  => 'noreply@livelingua.com',
	    'articlesEmail' => 'articles@livelingua.com',
	    'merchantID'    => env( 'LLGBL_MERCHANT_ID' ),
	    'cancelID'      => 'membership-payment.php',
	    'paypalURL'     => env( 'LLGBL_PAYPAL_URL' ),
	    'paypaIPN'      => 'students/php/pp-listener.php',
	    'academicDirectorName' => 'Laura',
	    'academicDirectorEmail' => 'directora@livelingua.com',
	    'supportEmail'  => 'support@livelingua.com',
	    'mainPhone'     => '(339) 499-4377',
	    'currencyCode'  => 'en_US',
	    'siteSubject'   => 'language',
	    'findPageURL'   => 'find-language-school/',
	    'searchPageURL' => 'language-schools/',
	    'postAdPageURL' => 'find-students/',
	    'postNewAdPageURL'  => 'post-ad.php',
	    'articleGeneralCategory' => 'General Language Learning',
	    'pageHeaderLogo' => 'img/live-lingua-schoolfinder-logo.png',
	    'pageFooterLogo' => 'img/onmutu-footer-logo.png',
	    'defaultArticleImage' => 'img/article-image.jpg',
	    'RefProgramCode' => env( 'LLGBL_REF_CODE_COOKIE_NAME' ),
	    'skype'         => 'live.lingua',
	    'phone_us'      => '+1 (781) 519-9984',
	    'phone_mexico'  => '+52 (442) 171-1306',
	    'address'       => [
	    	'street'    => '90 Canal Street, 4th Floor',
		    'city'      => 'Boston',
		    'state'     => 'MA',
		    'postalCode' => '02114',
		    'country'   => 'USA'
	    ]
    ],

	'languages' => [
		'English', 'Spanish', 'French', 'German', 'Italian', 'Portuguese', 'Chinese', 'Japanese', 'Korean', 'Russian',
		'Arabic'
	],

	'languages_favourite' => [
		'Spanish', 'English', 'French', 'German', 'Italian', 'Japanese', 'Korean', 'Chinese'
	],

    'top_languages_menu' => [ 'spanish', 'english', 'french', 'german', 'chinese', 'language-lessons' ],

	'resourses' => [ // $uri => [ $name, $description ]
		'/blog/'                => [ 'pages.resourses.blog',        'pages.resourses.blog_ttl' ],
		'/project/'             => [ 'pages.resourses.project',     'pages.resourses.project' ],
		'/twiducate/'           => [ 'pages.resourses.twiducate',   'pages.resourses.twiducate_ttl' ],
		'/gift-cards/'          => [ 'pages.resourses.gift_cards',  'pages.resourses.gift_cards' ],
		'/our-method.php'       => [ 'pages.resourses.our_method',  'pages.resourses.our_method' ],
		'/language-levels.php'  => [ 'pages.resourses.levels',      'pages.resourses.levels' ],
		'/our-story.php'        => [ 'pages.headers.about',         'pages.headers.about' ],
		'/staff.php'            => [ 'pages.headers.staff',         'pages.headers.staff' ],
		'/contact-us.php'       => [ 'pages.headers.contact_us',    'pages.headers.contact_us' ]
	]

];
