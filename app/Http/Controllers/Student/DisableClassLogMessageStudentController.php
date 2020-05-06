<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\StudentController;
use App\Student;

/**
 * This class outputs nothing.
 * It just sets Inactive the class log messages notification flag for the student
 * and then redirects the user to the dashboard.
 *
 * Class DisableClassLogMessageStudentController
 * @package App\Http\Controllers\Student
 */
class DisableClassLogMessageStudentController extends StudentController
{
    protected function before()
    {
	    parent::before();

	    $student = $this->user->getInstance();
	    $student->classLogMessages = Student::CLASS_LOG_MESSAGES_INACTIVE;
	    $student->save();

	    $this->notification( __('student_profile.class_log_messages_disabled'), 'success' );
	    $this->redirectResponse = redirect(route('students', ['controller' => 'dashboard']));
    }
}
