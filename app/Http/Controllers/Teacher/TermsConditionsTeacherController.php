<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\TeacherController;

class TermsConditionsTeacherController extends TeacherController
{
    public function __invoke()
    {
	    /**
	     * TODO: Implement the functionality
	     */
	    return redirect( '/teachers/terms-conditions.php' );
    }
}
