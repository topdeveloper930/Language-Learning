<?php


namespace App\Services\Auth;


use App\LoginLog;

trait LegacyAuthTrait {



	public function doLegacyLogin( $user, $loginType )
	{
		if( !$user instanceof UserType )
			return;

		$_SESSION['userid'] = $user->getKey();
		$_SESSION['title']= $user->getTitle();
		$_SESSION['firstName']= $user->getFirstName();
		$_SESSION['lastName'] = $user->getLastName();
		$_SESSION['email']  = $user->getEmail();
		$_SESSION['login']='TRUE';
		$_SESSION['userType'] = $user->getType();
	}

	public function doLegacyLogout()
	{
		session_destroy();
	}

	protected function getArea()
	{
		return $this->area;
	}

	protected function modelName()
	{
		return $this->model_name;
	}

	public function userType()
	{
		return $this->user_type;
	}
}