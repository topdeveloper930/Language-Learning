<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware( [ 'auth:api', 'throttle:120' ] )
	->group(function (){
		Route::get('/teacher', 'TeacherController@index');
		Route::get('/teacher/{id}', 'TeacherController@show');
		Route::get('/teacher/email/{email}', 'TeacherController@byEmail');
		Route::match([ 'delete', 'put', 'patch' ], '/teacher/{id}/group/{group}', 'TeacherController@group');
		Route::get('/teacher/{id}/classes', 'ClassController@teacherClasses');
		Route::post('/teacher/{id}/usuario', 'TeacherController@usuario');

		Route::get('/teacher/{id}/unavailability/{tz?}/{tz_2?}', 'TeacherUnavailabilityController@show');

		Route::get('/student/{active}', 'StudentController@filter')->where('active', 'active|inactive');
		Route::resource('/student', 'StudentController');
		Route::patch('/student/{student}/{status}', 'StudentController@status')->where('status', 'active|inactive');

		Route::get('/student/{student}/credit/{course}', 'CreditCotroller@show');
		Route::post('/student/{student}/credit', 'CreditCotroller@store');
		Route::get('/student/{student}/classes', 'ClassController@studentClasses');

		Route::resource('/class', 'ClassController');

		Route::resource('/activecampaign/contact', 'ActiveCampaignContactController');
	});

/**
 * These are routes that does not require authentication: callbacks, webhooks etc.
 */
Route::middleware( [ 'throttle:120' ] )
	->group(function (){
		Route::post('/webhook/{gateway}', [
			'as'    => 'webhook',
			'uses'  => 'WebhookController'
		])->where('gateway', 'stripe|paypal');
	});