<?php

return [
	// Minimum advance time that student allowed to schedule a class (hours)
	'schedule_advance'      => '24',
	'schedule_from'         => '+24 hours', // Notification time. Must be valid argument for PHP strtotime.
	'schedule_forth'        => '+1 month',  // How much forward can student schedule a class. Must be valid argument for PHP strtotime.
	'trial_class_length'    => 60,         // Duration of the trial class, minutes
	'lesson_start_step'     => 30,         // Lesson start time step, minutes
	'cancel_advance'        => '24',
	'cancel_from'           => '+24 hours', // Notification time. Must be valid argument for PHP strtotime.
	'purchase_hours'        => 10,
	'classroom_ready'       => env('CLASSROOM_READY', 0), // Set this env const to "1" when classroom functionality implemented
	/*
	 *  Max minutes since class start that class is available to enter for students and teacher.
	 */
	'max_class_tolerance'   => 120, // Assume max lesson length of 2 hours - should permit student/teacher to re-enter an interrupted class.
	'languages' => [
		'English', 'Spanish', 'French', 'German', 'Italian', 'Portuguese', 'Russian', 'Japanese', 'Chinese',
		'Korean', 'Arabic'
	],
	'num_students' => [
		1 => '1-on-1 (Private)',
		2 => '2 Students (Group)',
		3 => '3 Students (Group)'
	],
	'teacher_can_comment_salary_till' => env('TEACHER_CAN_COMMENT_SALARY_TILL', 10), // Date of the next month
	'profile_image_stub'              => 'img/profiles/no-profile-image.jpg'
];