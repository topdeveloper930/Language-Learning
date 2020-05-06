<?php

/**
 * Ajax routes for guest user
 */

Route::resource('location/country', 'CountryAjaxController', ['only' => ['index', 'show']]);
Route::resource('location/region', 'RegionAjaxController', ['only' => ['index', 'show']]);