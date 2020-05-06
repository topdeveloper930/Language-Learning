<?php

/**
 * Student routes
 */

Route::any('{controller}/{id?}', [
	'as'    => 'students',
	'uses'  => 'StudentEntryPoint@callClass'
]);

Route::get('ajax/{controller}/{id?}/{action?}', [
	'as'    => 'student_ajax',
	'uses'  => 'StudentEntryPoint@callAjax'
]);