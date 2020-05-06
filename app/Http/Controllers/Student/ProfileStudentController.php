<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\StudentController;

class ProfileStudentController extends StudentController
{
	protected $translation = 'student_profile.js';

	protected $js = [ 'jquery3_4', 'typeahead', 'student_profile' ];
}
