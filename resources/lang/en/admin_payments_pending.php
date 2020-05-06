<?php

return [
	'student_payments' => 'Pending Student Payments',
	'remark'           => '(*optional) Reason or remark for the action.',
	'js' => [
		'id'          => '#',
		'name'        => 'Name',
		'gateway'     => 'Gateway',
		'gateway_id'  => 'Gateway #',
		'for'         => 'For',
		'amount'      => 'Amount, USD',
		'date'        => 'Date & Time',
		'actions'     => 'Actions',
		'approve'     => 'Approve',
		'deny'        => 'Deny',
		'view'        => 'View the transaction details.',
		'reason'      => 'Reason',
		'comment'     => 'Comment',
		'view_h'      => 'Transaction Details',
		'approve_h'   => 'Transaction Confirmation',
		'deny_h'      => 'Transaction Decline',
		'close'       => 'Close',
		'return'      => 'Return',
		'for_course'  => ':h hour(s) of :c (:t)',
		'purchase_of' => [
			1 => 'Purchase of :h hour(s) of :c course private lessons',
			2 => 'Purchase of :h hour(s) of :c course group classes (2 students)',
			3 => 'Purchase of :h hour(s) of :c course group classes (3 students)'
		],
		'giftcard' => [
			'No gift card used',
			'Used gift card :g for $:sUS'
		],
		'coupon' => [
			'No coupon used',
			'Discount of :d with coupon :c (:v)'
		],
		'referral_credits' => [
			'No referral credits used',
			'Referral credits for $:rUS deducted'
		]
	]
];