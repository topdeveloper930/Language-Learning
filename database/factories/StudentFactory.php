<?php

/*
|--------------------------------------------------------------------------
| Student Factory
|--------------------------------------------------------------------------
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Student::class, function (Faker\Generator $faker) {
	static $password;

	$gender = $faker->randomElement(['male', 'female']);

	return [
		'visitID' => $faker->numberBetween(),
		'title' => $faker->title( $gender ),
		'firstName' => $faker->firstName( $gender ),
		'lastName' => $faker->lastName,
		'email' => $faker->unique()->safeEmail,
		'password' => $password ?: $password = str_random( 8 ),
		'lang' => 'EN',
		'dateOfBirth' => $faker->dateTimeBetween( '-100 years', '-4 years' )->format( 'Y-m-d' ),
		'ageRange' => 'Unknown',
		'information' => $faker->text(),
		'timezone' => $faker->timezone,
		'skype' => $faker->slug,
		'phone' => $faker->phoneNumber,
		'paypalEmail' => $faker->safeEmail,
		'country' => $faker->country,
		'state' => '',
		'city' => $faker->city,
		'profileImage' => '/img/profiles/no-profile-image.jpg',
		'studentNotes' => $faker->text(),
		'mailingList' => 'Active',
		'classLogMessages' => 'Active',
		'classReminder' => 'Active',
		'active' => 'Active',
		'createDate' => $faker->dateTimeThisDecade,
		'remember_token' => str_random(10)
	];
});

$factory->state(App\Student::class, 'with_studentID', function ( $faker ) {
	/** @var $faker Faker\Generator */
	return [
		'studentID' => $faker->numberBetween(0, 50000),
	];
});

$factory->state(App\Student::class, 'inactive', function ( $faker ) {
	return [
		'active' => 'Inactive',
	];
});
