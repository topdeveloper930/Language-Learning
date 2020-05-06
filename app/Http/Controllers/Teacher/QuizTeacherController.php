<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\TeacherController;

class QuizTeacherController extends TeacherController
{
    protected function before()
    {
	    parent::before();

	    $this->redirectResponse = redirect(route('page', ['controller' => 'quiz', 'id' => 'teacher']));
    }
}
