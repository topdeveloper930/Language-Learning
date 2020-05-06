<?php

/**
 * Ajax routes for authenticated user (teacher, student, group and admin user)
 */

Route::resource('event', 'EventAjaxController', ['only' => ['index', 'store', 'update', 'destroy']]); // "index" here needed (despite method not implemented) for composing "destroy" url for JS - in views\student\dashboard.blade.php

Route::resource('calendar-external', 'CalendarExternalAjaxController');

Route::post('student/{id}/photo', 'StudentsAjaxController@photo');
Route::put('student/{id}/{form}', 'StudentsAjaxController@update');

Route::post('teacher/{id}/photo', 'TeachersAjaxController@photo');
Route::put('teacher/{id}/{form}', 'TeachersAjaxController@update');

Route::get('teacher/availability-stats', 'TeachersAjaxController@availability_stats');
Route::get('{language}/courses', 'CoursesAjaxController@index');