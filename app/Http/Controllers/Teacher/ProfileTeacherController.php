<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\TeacherController;

class ProfileTeacherController extends TeacherController
{
    protected $js = [ 'jquery3_4', 'typeahead', 'teacher_profile' ];

    protected $translation = 'teacher_profile.js';
}
