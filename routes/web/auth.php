<?php

/**
 * Authentication routes
 */

//\Illuminate\Support\Facades\Auth::routes();

Route::get('/{role}/login', [
	'as' => 'login',
	'uses' => 'Auth\LoginController@showLoginForm'
]);

Route::get('/login', [
	'as' => 'commonLogin',
	'uses' => 'Auth\LoginController@showLoginForm'
]);

Route::get('/{role}/logout', [
	'as' => 'logout',
	'uses' => 'Auth\LoginController@logout'
]);

Route::get( 'auth/{provider}', [
	'as' => 'sn_callback',
	'uses' => 'Auth\ExternalAuth@handleProviderCallback'
] )->where('provider', 'google|facebook');

Route::get( 'auth/{provider}/{role}', [
	'as' => 'sn_redirect',
	'uses' => 'Auth\ExternalAuth@redirectToProvider'
] )->where(['role' => 'student|teacher|admin|group', 'provider' => 'google|facebook']);


Route::post('/auth/login', [
	'as' => '',
	'uses' => 'Auth\LoginController@login'
]);

Route::get( 'auth/forget', [
	'as' => 'password.request',
	'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm'
] );

Route::post( 'auth/email', [
	'as' => 'password.email',
	'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
] );

Route::post( 'auth/reset', [
	'as' => 'reset',
	'uses' => 'Auth\ResetPasswordController@reset'
] );

Route::get( 'auth/{role}/{token}/{email}', [
	'as' => 'password.reset',
	'uses' => 'Auth\ResetPasswordController@showResetForm'
] );