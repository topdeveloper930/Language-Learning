<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/
$languages_taught = 'english|french|german|portuguese|italian|russian|arabic|chinese|japanese|korean';

$verb_groups = 'ar|ir|er|regular|irregular|reflexive';

/* This route (for SEO sake) needed - the tutor's name in the URI is important */
Route::get('{language}/tutors/{name}/{id}', [
	'as'    => 'tutor',
	'uses'  => 'TutorPageController'
])->where('language', $languages_taught);

/* This is for Spanish tutors */
Route::get('tutors/{name}/{id}', [
	'uses'  => 'TutorPageController'
]);

Route::get('verbs/{group}', [
	'as'	=> 'verbs_group',
	'uses'	=> 'VerbsGroupPageController'
])->where('group', $verb_groups);

Route::get('{language}/{controller?}/{id?}', [
	'as'    => 'language',
	'uses'  => 'LanguageEntryPoint@callClass'
])->where([ 'language' => $languages_taught ]);

Route::get('/', 'HomePageController');

Route::any('{controller}/{id?}', [
	'as'    => 'page',
	'uses'  => 'PageEntryPoint@callClass'
]);

Route::get('{controller}/search/{search?}', [
	'as'    => 'search',
	'uses'  => 'PageEntryPoint@callSearch'
])->where(['search' => '.*']);