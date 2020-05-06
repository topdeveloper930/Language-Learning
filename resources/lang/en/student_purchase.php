<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Purchase Page Lines
	|--------------------------------------------------------------------------
	|
	*/

	'purchase_classes'      => 'Purchase classes',
	'purchase'              => 'Your Live Lingua credits never expire.',
	'course_language'       => 'Course &amp; language',
	'language'              => 'Language',
	'choose'                => 'Please, choose',
	'program'               => 'Program',
	'number_of_hours'       => 'Number of hours',
	'class_size'            => 'Class size',
	'coupon'                => 'Coupon',
	'gift_card'             => 'Use <a href=":url">gift card</a>?',
	'payment'               => 'Payment',
	'stripe'                => 'Credit Card',
	'paypal'                => 'PayPal',
	'check'                 => 'Check',
	'check_payment'         => 'Check Payment',
	'wire_transfer'         => 'Wire Transfer',
	'transfer'              => 'Wire Transfer',
	'instruction'           => '[LIVE LINGUA] - :gateway Instructions',
	'transfer_selected'     => 'You have selected wire transfer as your preferred payment method to pay for your <u>:hours hours of :course</u> lessons. To make this payment please follow these instructions.',
	'initiate_transfer'     => 'Go to your bank - either physically or online - and initiate a wire transfer to the following account:',
	'review_costs'          => 'To review the costs, please see the <a href=":link">pricing page</a>',
	'check_selected'        => 'You have selected US dollar check as your preferred payment method to pay for your <u>:hours hours of :course</u> lessons. To make this payment please follow these instructions.',
	'enough_costs'          => 'Make sure your bank account has the <b>$:sum U.S.</b> due for the classes in it.',
	'check_out_to'          => 'Make the US dollar check out to <b>:to</b> and mail it to the following address:',
	'mail_us'               => 'Once the check has been mailed please email us at :email and let us know so we can be on the lookout for it.',
	'transfer_initiated'    => 'Once the transfer has been initiated please email us at :email and let us know the transfer has been initiated.',
	'term_deposit'          => 'Once the check arrives we will deposit it within 1 business day. It can take 3-5 business days for a check to clear.',
	'we_check'              => 'We will check the account every business day depositing the check and confirm for you the receipt of the payment as soon as it clears so you can start your classes.',
	'we_transfer'           => 'We will check the account every business day after receiving the email and confirm for you the receipt of the payment as soon as it clears so you can start your classes.',
	'card_number'           => 'Card number',
	'exp_date'              => 'Exp date',
	'cvc'                   => 'CVC',
	'notification'          => '<strong>Important:</strong> By enrolling in paid lessons with us you are agreeing to the Live Lingua <a href=":terms">Terms & Conditions</a>. Please make sure to review them before proceeding. Live Lingua is owned and operated by :owner.',
	'transfer_min_purchase' => '<b>NOTE:</b> International wire-transfer is only possible for enrollment of :hours hours or more at the same time. This is due to the large fee our bank charges us to receive international wire transfers.',
	'no_data'               => 'No data',
	'no_transaction'        => 'Transaction not found or already processed',
	'service_unavailable'   => 'Service temporary unavailable. Try another payment method.',
	'hold_for_check'        => 'We cannot confirm the transaction right away due to the payment service temporary unavailability. But we will check it manually as soon as possible. Please, check the transaction charge from your payment card or the result on our site prior to repeating the attempt.',
	'missing_calculation'   => 'There is missing the purchase calculation data',
	'js' => [
		'balance_due'       => 'Balance due',
		'cost'              => 'Cost:',
		'coupon_for'        => 'Coupon for',
		'giftcard'          => 'Gift card:',
		'referral_credits'  => 'Referral Credits',
		'language'          => 'What language would you like to learn with Live Lingua?',
		'program'           => 'Is this payment for our Standard Lessons (our most popular option) or are you enrolling in one of our specialized course like TOEFL, DELE, DELF, etc.',
		'hoursPurchased'    => 'How many hours of classes would you like to purchase at this time.<br><br>If you are signing up for any of our professional classes such as English for Business, or Spanish for Tourists then this field will be auto-completed since those courses have fixed lengths.',
		'classSize'         => 'How many people are going to be taking this class at the same time. If this is a private lesson, then just leave it as 1-on-1.<br><br>If you are going to take the lessons at the same time as a friend of family the cost for the extra students is 50% of the additional students (up to 3 students). This means that if you are taking Spanish lessons with 2 friends and purchase 50 hours at the cost per hour would be $9.00 U.S. for the first student and $4.50 for the second and third students. So the total cost per hour would be $18.00 U.S. (or $6.00 U.S. per student per hour).',
		'coupon'            => 'This field is optional and only needs to be entered if you have Live Lingua coupon you would like to use. Please enter the code. The coupon will automatically be verified and applied.',
		'giftCard'          => 'This field is optional and only needs to be entered if you have Live Lingua gift card you would like to claim. Please enter the <a href="' . route( 'page', [ 'controller' => 'gift-card' ] ) . '">Live Lingua gift card</a> number. The number you put in will automatically be verified and applied.<br><br>You can enroll in more hours than your gift card amount. In this case you will simply be asked to pay the difference.',
		'purchase_classes'  => 'Purchase classes',
		'pay_paypal'        => 'Pay with PayPal',
		'send_instructions' => 'Send payment instructions',
		'cvc'               => 'The CVV/CVC code (Card Verification Value/Code) is located on the back of your credit/debit card on the right side of the white signature strip; it is always the last 3 digits in case of VISA and MasterCard.<br><img id="imagelightbox" src="data:image/png;base64,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">',
		'itemDescription'   => ':type course (:kind)',
		'private'           => 'Private Classes',
		'group'             => 'Group Classes',
		'hours_purchased'   => 'Hours must be greater than minimum value',
		'selected_course'   => 'Program is required field',
		'something_wrong'   => 'Something went wrong. Please, report this error to the class coordinator.'
	]
];
