<?php

/*
|--------------------------------------------------------------------------
| Teacher Factory
|--------------------------------------------------------------------------
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Teacher::class, function (Faker\Generator $faker) {
	static $password;

	$gender = $faker->randomElement(['male', 'female']);
	$language = $faker->randomElement(['Spanish', 'English', 'Italian', 'German', 'Japanese']);

	return [
		'title' => $faker->title( $gender ),
		'titleID' => 0,
		'firstName' => $faker->firstName( $gender ),
		'lastName' => $faker->lastName,
		'email' => $faker->unique()->safeEmail,
		'password' => $password ?: $password = str_random( 8 ),
		'skype' => $faker->slug,
		'phone' => $faker->phoneNumber,
		'timezone' => $faker->timezone,
		'timezoneID' => 0,
		'languagesSpoken' => $faker->paragraph,
		'languagesSpokenES' => $faker->paragraph,
		'teacherIntroduction' => $faker->paragraph,
		'teacherIntroductionES' => $faker->paragraph,
		'languagesTaught' => [$language],
		'dialectsTaught' => [$language],
		'coursesTaught' => [],
		'agesTaught' => ['College', 'Adults', 'Seniors'],
		'paymentEmail' => $faker->safeEmail,
		'paymentNotes' => $faker->paragraph,
		'countryOrigin' => $faker->country,
		'countryOriginID' => 0,
		'stateOrigin' => $faker->word,
		'stateOriginID' => 0,
		'cityOrigin' => $faker->city,
		'street1Residence' => $faker->streetName,
		'street2Residence' => $faker->streetAddress,
		'countryResidence' => $faker->country,
		'countryResidenceID' => 0,
		'stateResidence' => $faker->word,
		'stateResidenceID' => 0,
		'cityResidence' => $faker->city,
		'zipResidence' => $faker->postcode,
		'startedTeaching' => $faker->year(),
		'teachingStyle' => $faker->sentence,
		'teachingStyleID' => $faker->numberBetween(1, 4),
		'teachingStyleES' => $faker->paragraph,
		'education' => $faker->paragraph,
		'educationES' => $faker->paragraph,
		'workExperience' => $faker->paragraph,
		'workExperienceES' => $faker->paragraph,
		'hobbies' => $faker->paragraph,
		'hobbiesES' => $faker->paragraph,
		'favoriteWebsite' => $faker->url,
		'favoriteMovie' => $faker->words,
		'favoriteMovieES' => $faker->words,
		'favoriteFood' => $faker->paragraph,
		'favoriteFoodES' => $faker->paragraph,
		'countriesVisited' => $faker->paragraph,
		'countriesVisitedES' => $faker->paragraph,
		'bucketList' => $faker->paragraph,
		'bucketListES' => $faker->paragraph,
		'teacherNotes' => $faker->paragraph,
		'teacherScore' => $faker->numberBetween(0, 2500),
		'activeTeacher' => 'Active',
		'newStudents' => 1,
		'profileImage' => 'img/profiles/no-profile-image.jpg',
		'createDate' => $faker->dateTime()->format('Y-m-d H:i:s'),
		'usuario_id' => $faker->randomNumber(3),
		'gender' => $gender
	];
});

$factory->state(App\Teacher::class, 'with_teacherID', function ( $faker ) {
	/** @var $faker Faker\Generator */
	return [
		'teacherID' => $faker->numberBetween(1000, 2000),
	];
});

$factory->state(App\Teacher::class, 'inactive', function ( $faker ) {
	return [
		'activeTeacher' => 'Inactive'
	];
});

$factory->state(App\Teacher::class, 'no_new_students', function ( $faker ) {
	return [
		'newStudents' => 0
	];
});
