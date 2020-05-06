<?php

Route::any('{controller}/{id?}', [
	'as'    => 'admin',
	'uses'  => 'AdminEntryPoint@callClass'
]);

Route::any('ajax/{controller}/{id?}/{action?}', [
	'as'    => 'admin_ajax',
	'uses'  => 'AdminEntryPoint@callAjax'
]);