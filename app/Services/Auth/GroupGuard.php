<?php


namespace App\Services\Auth;


class GroupGuard extends LegacyGuard {

	protected $area         = 'group';
	protected $user_type    = 'group';
	protected $model_name   = 'group';

}