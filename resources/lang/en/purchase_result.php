<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Purchase Result Page Lines
	|--------------------------------------------------------------------------
	|
	*/

	'purchase_result'  => 'Purchase Result',
	'completed'        => 'Transaction Successful',
	'denied'           => 'Transaction Cancelled',
	'pending'          => 'Transaction Pending',
	'thankyou'         => 'Thank You. Gracias. Arigato. Danke. 谢谢.',
	'was_completed'    => 'Your payment of <b>$:amount US</b> for <u>:hours of :course Course</u> was completed.',
	'hours'            => ':count hour|:count hours',
	'cancel_reason'    => '[0] Your payment was cancelled.|[1] Your payment was cancelled due to the following reason: <code>:reason</code>',
	'transaction_id'   => 'Transaction ID: :id',
	'instant_pay'      => 'We are waiting for the payment confirmation. Please, check this page later, if the checkout process was successful.',
	'retry'            => 'Retry paying the purchase',
	'review'           => 'Review the purchase data',
	'cancel'           => 'Cancel the purchase',
	'details'          => 'The transaction details',
	'course'           => 'Course',
	'hours_cnt'        => 'Hours',
	'course_type'      => '[1] :course private classes|[2,3] :course group classes (:count)',
	'cost'             => 'Cost',
	'coupon_for'       => 'Coupon for :for',
	'referral_credits' => 'Referral bonus',
	'total'            => 'Total',
	'non_instant_pay'  => 'We have sent a copy of the instructions to your email so that you can have them on file.<br>You will get an email notification upon the transaction completed.',
	'stripe' => [
		'duplicate'             => 'Duplicate payment. A transaction with identical amount and credit card information was submitted very recently.',
		'fraudulent'            => 'Please, contact your card issuer for more information.',
		'requested_by_customer' => 'The cancelation requested by the customer',
		'abandoned'             => 'Abandoned payment process. Session expired.'
	],
	'paypal' => [
		'voided' => 'The transaction has been canceled'
	],
	'transaction_delete_validation_fail' => 'Transaction not found or already processed.',
	'canceled_by_user'                   => 'Cancelled by user',
	'cancellation'                       => 'Cancellation of the purchase',
	'no'                                 => 'No, return',
	'ok'                                 => 'Yes, proceed',
	'warning'                            => '<p>You are about to cancel the purchase of :hours of <b>:classes</b>. Are you sure?</p>'
];