<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Event::class, function (Faker\Generator $faker) {

	$eventStart = $faker->dateTimeBetween( '+1 days', '+7 days');
	$studentID = $faker->numberBetween(0, 50000);

    return [
	    'calendarID'  => $faker->numberBetween(),
	    'teacherID'   => $faker->numberBetween( 0, 500 ),
	    'studentID'   => $studentID,
	    'entryTitle'  => $faker->text(),
	    'eventStart'  => $eventStart->format( 'Y-m-d H:00:00' ),
	    'eventEnd'    => $eventStart->add( new DateInterval( "PT1H" ) )->format( 'Y-m-d H:00:00' ),
	    'active'      => 1,
	    'numStudents' => 1,
	    'createdBy'   => 'student:' . $studentID,
	    'createDate'  => date( 'Y-m-d H:i:s' ),
    ];
});

$factory->state(App\Event::class, 'cancelled', function ( $faker ) {
	return [
		'active' => 0
	];
});