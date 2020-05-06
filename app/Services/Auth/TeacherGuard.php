<?php


namespace App\Services\Auth;


class TeacherGuard extends LegacyGuard {

	protected $area         = 'teachers';
	protected $user_type    = 'teacher';
	protected $model_name   = 'teacher';

}