<?php


namespace App\Services\Auth;


use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

abstract class LegacyGuard extends SessionGuard {

	use LegacyAuthTrait;

	const NS = '\App\\';


	/**
	 * @var UserType
	 */
	protected $user_profile;

	protected $user_type;
	protected $model_name;
	protected $area;

	/**
	 * Check if the legacy user ID stored in SESSION and obtain the user by that ID.
	 *
	 * @return null|AuthenticatableContract
	 */
	public function getUserFromLegacySession()
	{
		if( $this->user_profile AND $this->user_profile->exists() )
			return $this->user_profile->usuario;

		if( !empty( $_SESSION[ 'userid' ] )
		        AND $this->user_type == $_SESSION[ 'userType' ]
		        AND ( $model = $this->getProfileModel() ) instanceof UserType )
		{
			$this->user_profile = $model->find( $_SESSION['userid'] );

			if ( $this->user_profile->exists() )
				return $this->user_profile->usuario;
		}

		return null;
	}


	/**
	 * Get the currently authenticated user.
	 * Add legacy session check in addition to the default procedure.
	 *
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function user()
	{
		$user = parent::user();

		$user OR $user = $this->getUserFromLegacySession();

		return $this->user = $user;
	}

	public function login(AuthenticatableContract $user, $remember = false)
	{
		parent::login( $user, $remember );

		$this->doLegacyLogin( $this->user->{$this->modelName()}, $this->user_type );
	}

	/**
	 * Remove the user data from the session and cookies.
	 *
	 * @return void
	 */
	protected function clearUserDataFromStorage()
	{
		$this->doLegacyLogout();
	}

	protected function getProfileModel()
	{
		$model = static::NS . ucfirst( $this->modelName() );

		return ( class_exists( $model ) ) ? new $model : null;
	}
}