<?php

Route::any('{controller}/{id?}', [
	'as'    => 'teachers',
	'uses'  => 'TeacherEntryPoint@callClass'
]);

Route::get('ajax/{controller}/{id?}/{action?}', [
	'as'    => 'teacher_ajax',
	'uses'  => 'TeacherEntryPoint@callAjax'
]);