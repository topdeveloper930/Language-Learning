<?php

namespace App\Http\Controllers\Ajax;

use App\CourseList;
use App\Http\Controllers\AjaxController;
use Illuminate\Support\Facades\Validator;

class CoursesAjaxController extends AjaxController
{
	protected $ajaxOnly = true;

    public function index( $language )
    {
    	$language = ucfirst( $language );

	    Validator::make([ 'language' => $language ], [
		    'language' => 'required|string|exists:courseList,language'
	    ])->validate();

	    return response()->json(
		    CourseList::where('language', $language)
		              ->orderBy('courseListID')
		              ->get(['courseType', 'courseHours'])
		              ->toArray()
	    );
    }
}
