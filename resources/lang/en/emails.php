<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Emailing Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used in emails.
    |
    */

    'subject' => [
    	'student' 	=> [
		    'class_scheduled' => '[LIVE LINGUA]-CLASS SCHEDULED- :date',
		    'class_cancelled' => '[LIVE LINGUA]-CLASS CANCELLED- :date',
		    'class_changed'   => '[LIVE LINGUA]-CLASS CHANGED- :date',
		    'no_teacher'      => '[LIVE LINGUA] - Payment Received',
		    'payment_success' => '[LIVE LINGUA] - Payment Successful',
		    'class_logged'    => '[LIVE LINGUA] - New Class Logged',
		    'low_on_classes'  => '[LIVE LINGUA] - Low On Classes',
		    'no_hours'        => '[LIVE LINGUA] - No Hours Remaining',
		    'progress_report' => '[LIVE LINGUA]- New Progress Report Available!'
	    ],
    	'teacher' 	=> [
		    'class_scheduled' => '[LIVE LINGUA]-CLASS SCHEDULED- :date - :student',
		    'class_cancelled' => '[LIVE LINGUA]-CLASS CANCELLED- :date - :student',
		    'class_changed'   => '[LIVE LINGUA]-CLASS CHANGED- :date - :student',
		    'payment_success' => '[LIVE LINGUA] - :student paid for :hours of :lessons lessons' // '[LIVE LINGUA] - Mrs. Jane Doe paid for 7.5 hours of English-Standard English lessons'
	    ],
	    'admin'		=> [
		    'no_teacher'      => '[LIVE LINGUA] - INVOICE - :student (Action Required)',
			'payment_success' => '[LIVE LINGUA] - INVOICE - :student',
			'affiliate_received'=> '[LIVE LINGUA] - Affiliate Received',
		],
		'gift_card' => [
			'new_signup'	  => '[LIVE LINGUA]- Live Lingua Account Information',
			'buyer'			  => '[LIVE LINGUA] - Gift Card Purchased',
			'receiver'		  => ':sender sent you a Live Lingua gift card!',
			'admin'			  => '[LIVE LINGUA] - INVOICE - Gift Card Purchase - :sender'
		],

	    'forgot' => '[LIVE LINGUA] - Forgot :Role Password'
	],
	'from' => 'Message from :who!',
	'unsubscribe' => 'Unsubscribe by <a href=":url" title="Unsubscribe" style="color:#0066CC;" target="_blank">clicking here</a>.',
	'copyright' => '&copy; :year :company. All rights reserved.',
	'salutation' => 'Have a great day.',
	'button_problem' => 'If you’re having trouble clicking the ":actionText" button, copy and paste the URL below into your web browser: <a href=":actionUrl">:actionUrl</a>',
	'class_scheduled' => [
		'student' => 'This is an automated message from the Live Lingua system to let you know that your class with :teacher has been scheduled for :dt your time as you requested.',
		'teacher' => [
			'student' => 'This is an automated message from the Live Lingua system to let you know that your student :student has just scheduled a class with you on :dt your time.',
			'admin' => 'This is an automated message from the Live Lingua system to let you know that your class coordinator has just scheduled for you a class with your student :student on :dt your time.'
		]
	],
    'class_changed' => [
	    'student' => 'This is an automated message from the Live Lingua system to let you know that your class with :teacher has been rescheduled for :dt your time as you requested.',
	    'teacher' => [
		    'student' => 'This is an automated message from the Live Lingua system to let you know that your student :student has just changed a class with you on :dt your time.',
		    'admin' => 'This is an automated message from the Live Lingua system to let you know that your class coordinator has just changed for you a class with your student :student on :dt your time.'
	    ]
    ],
    'class_cancelled' => [
	    'student' => 'This is an automated message from the Live Lingua system to let you know that your class with :teacher scheduled for :dt has been cancelled as you requested.',
	    'teacher' => [
		    'student' => 'This is an automated message from the Live Lingua system to let you know that your student :student has just cancelled the class that was scheduled with you on :dt.',
		    'admin' => 'This is an automated message from the Live Lingua system to let you know that your class coordinator has just cancelled the class with your student :student that was scheduled on :dt.'
	    ]
    ],
	'class_scheduled_remark' => [
		'student' => 'If you need to make any change to this class, you can do so by rescheduling the class through your Live Lingua dashboard or simply emailing your teacher directly. If you need any assistance, just reply to this email and your class coordinator can assist you. Thank you.',
		'teacher' => 'If you need to make any change to this class, please contact the student directly to arrange the change and then update the class in the system. If you need any assistance, just reply to this email and your class coordinator can assist you. Thank you.'
	],
    'class_cancelled_remark' => 'If you need any assistance please just reply to this email and your class coordinator will get back to you. Thank you.',
	'refer' => [
		'subject' => '[LIVE LINGUA] - Invitation from :student',
		'good_day' => 'Good day :name,',
		'use_link' => 'Please, use this [link](:link) to register in Live Lingua system.',
		'have_discount' => 'To show our appreciation, we are happy to offer a discount of :discount on your first classes purchase. Simply copy and paste the referral code at check out:',
		'assistance' => 'If you need any assistance, please reply to this email and our class coordinator will get back to you.',
		'have_great_day' => 'Have a great day.',
		'referrer_says' => ':referrer says:'
	],
	'payment' => [
		'hours'          => '{1} :count hour |[*] :count hours',
		'login'          => 'Login',
		/* Coordinator */
		'success_coordinator' => 'This message is to confirm that the student *:accost* just paid **$:amount US** for ***:hours of :language (:course) lessons*** with their :tutors.',
		'no_action_required'  => 'This email is just a receipt of payment for our email records. No further action is necessary at this time.',
		'no_teacher'          => '**NO TEACHER FOUND**',
		'to_coordinator'      => 'This student, *:accost* (:email), just paid **$:amount US** for ***:hours of :language (:course) lessons***.  But our system was not able to find their teacher.  Please check this students history to try to determine who their teacher is and manually assign the teacher to this student in the admin area. If the student signed up without first having a trial lesson, please contact them to assign them to a teacher.',
		/* Student */
		'to_student_success' => 'Thank you for your payment of **$:amount US** for ***:hours of :language (:course) lessons*** with your :tutors.',
		'hours_added'        => 'Your hours have been added to the Live Lingua system. If you have not already done so you can schedule your next class by logging in to your Live Lingua dashboard ([here](:url)) and clicking on *Schedule Class* to access your teachers calendar, or you can simply email your teacher directly to request a time and day.',
		'for_assist'         => 'As always, if there is anything we can do assist you, or to make your experience with Live Lingua better, don\'t hesitate to contact us. Just reply to this email and we will get right back to you.',
		'to_cancel'          => '**Class Cancellation Policy:**  *If a class is cancelled or rescheduled with less than 24-hour notice, the class will be billed by the teacher. Please make sure to review this and all of Live Lingua\'s terms and conditions on our website [here](:terms_url).*',
		'to_student_no_teacher_thankyou' => 'Thank you for your payment of **$:amount US** for ***:hours of :language (:course) lessons***.',
		'to_student_no_teacher' => [
			'Your hours have been added to the Live Lingua system.',
			'There was one small issue with the payment in that our system could not automatically identify who your teacher was. This can sometimes happen if you signed up for a different course than you were previously taking, a course different from what you registered for in your trial lesson or if you signed up without taking a trial lesson.',
			'Please rest assured that this is very easy for your class coordinator to fix.  They will resolve it right away and contact you via email to confirm so that you can continue with your classes right away.',
			'In the meantime if you have any questions, please feel free to reply to this email and we will get back to your right away.'
		],
		/* Teacher */
		'teacher_info'   => 'Just to let you know, your student *:student* just paid for ***:hours of :language (:course) lessons*** with you.',
		'teacher_success'   => [
			'If you have not already talked to them about their next class please contact them to schedule it right away.',
			'The new hours have been added to student record in the Live Lingua system.',
			'Thanks and keep up the good work.'
		]
	],

	'gift_card'			=> [
		'buyer'				=>	[
			'send_now'			=> 'As you requested, the gift card will be sent to <u> :recipient_fullname </u> right away.  They should get it via email in the next few minutes.  If you contact them and they have not gotten it yet, please ask them to check their spam folder.',
			'send_later'		=> 'As you requested the card will be sent to <u> :recipient_fullname </u> on <i>:deliveryTime</i>.',
			'print'				=> 'As you requested we will not email this gift card to <u> :recipient_fullname </u> so that you can print it out and give it to them in person.'
		],
		'receiver'			=>  [
			'birthday'			=> "Happy Birthday! To celebrate this special occasion ",
			'anniversary'		=> "Congratulations on your anniversary! To celebrate this special occasion ",
			'valentines'		=> "Happy Valentines Day! To celebrate this special occasion ",
			'wedding'			=> "Congratulations on the wedding! To celebrate this special occasion ",
			'fathers_day'		=> "Happy Father's Day! To celebrate this special occasion ",
			'mothers_day'		=> "Happy Mother's Day! To celebrate this special occasion ",
			'christmas'			=> "Merry Christmas! To celebrate this special occasion "
		]
	],
	'class_coordinator' => 'Class Coordinator',
	'office_hours'      => 'Administrative Office Hours',
	'working_hours'     => 'Monday - Friday: 9:00 a.m. - 5:00 p.m. (GMT-5)',
	'classes_available' => 'Language Classes Available',
	'7_24'              => '7 days a week - 24 hours a day',
	'trial_no_show'     => 'I hope you are well. I received an email from your teacher :teacher saying that you were not able to make it to your scheduled trial class. If you would like to reschedule, please just let me know and I would be happy to arrange it for you.',
	'trial_missed'      => '[LIVE LINGUA] - Trial Class Missed',
	'your_ll_login'     => '[LIVE LINGUA]- Your Live Lingua Login!',
	'welcome'           => 'Welcome to Live Lingua!',
	'account_created'   => 'This is an automated message to let you know that we have created a free <b>Live Lingua Student Account</b> for you.  This free account will allow you to check your trial lesson date & time as well as your teacher information.  From within your student account you will also be able to <u>enroll in more lessons</u> after the free trial lesson takes place.',
	'here_info'         => 'Here is the login information for your records.  We recommend changing your password as soon as you login to something that is easier for you to remember.',
	'username'          => '**Username: **:email',
	'password'          => '**Password: **:pass',
	'login_now'         => 'Login Now',
	'any_questions'     => 'If you have any questions or comments  please just reply to this email and let us know.  We will get back to you right away.',
	'class_logged'      => [
		'automated_message' => 'This is an automated message to let you know that your teacher **:teacher** has logged a class with you in our system. Here are the notes from the class for your review.',
		'see_class_logs'    => 'See Class Logs',
		'class_details'     => '**Class Details:** :details',
		'details'           => ':date - :minutes minutes - :course',
		'class_notes'       => '**Class Notes:**',
		'notes'             => '*:notes*',
		'review_class'      => 'You can review this class and all other classes by clicking on the *See Class Logs* button below.  If you have any questions about the class, please feel free to just reply to this email and your class coordinator will get right back to you. If you would prefer not to get notices of the class logs, you can disable these messages by clicking *[here](:link)*.'
	],
	'low_on_classes' => [
		'enjoy_lessons' => 'I hope you are enjoying your *Skype :Language lessons* with **:teacher**.  This email is just to let you know that our system shows that you are *running low on classes* (less than 3 hours remaining). If you would like to sign up for additional lessons now so that there are no interruptions in your classes you can do so easily by clicking on the button below, or by simply replying to this email.  We look forward to continuing to work with you.',
		'purchase_more' => 'Purchase More Lessons!'
	],
	'no_hours' => 'I hope you are having a great day! This email is just to let you know that our system shows that you have *no Skype :Language lessons* with **:teacher** remaining.  If you would like to continue with your classes all you need to do is click on the button below to purchase more.  Otherwise if there is anything else I can assist you just let me know.',
    'progress_report' => [
	    'report_completed' => 'Your :Language teacher **:teacher** has just completed your most recent **Live Lingua progress report**. To see the report and your current :Language level based on the [ACTFL language proficiency scale](:link) just log in to your Live Lingua account and check it out!',
	    'action'           => 'Check Report'
    ]

];
