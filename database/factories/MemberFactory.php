<?php

/*
|--------------------------------------------------------------------------
| Member Factory
|--------------------------------------------------------------------------
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Member::class, function (Faker\Generator $faker) {
	static $password;

	return [
		'usuario_id' => $faker->numberBetween(0, 1000),
		'password' => $password ?: $password = str_random( 8 ),
		'firstName' => $faker->firstName($faker->randomElement(['male', 'female'])),
		'lastName' => $faker->lastName,
		'email' => $faker->unique()->safeEmail,
		'userType' => 'admin',
		'userCode' => str_random(32),
		'image' => '/img/profiles/no-profile-image.jpg',
		'complete' => 63,
		'mailingList' => 1,
		'premium' => '0',
		'active' => 1,
		'lastUpdated' => $faker->dateTimeThisDecade,
		'lastLogin' => $faker->dateTime()->format('Y-m-d H:i:s')
	];
});

$factory->state(App\Member::class, 'super-admin', function ( $faker ) {
	return [
		'userType' => 'super-admin',
	];
});

$factory->state(App\Member::class, 'inactive', function ( $faker ) {
	return [
		'active' => 0,
	];
});
