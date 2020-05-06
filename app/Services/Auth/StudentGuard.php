<?php


namespace App\Services\Auth;


use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class StudentGuard extends SessionGuard {

	use LegacyAuthTrait;

	protected $area      = 'students';
	protected $user_type = 'student';
	protected $model_name = 'student';

	/**
	 * Check if the student ID stored in SESSION
	 *
	 * @return null|AuthenticatableContract
	 */
	public function getUserFromLegacySession()
	{
		if( !empty( $_SESSION['userid'] ) AND $this->user_type == $_SESSION[ 'userType' ] )
			return $this->provider->retrieveById( $_SESSION[ 'userid' ] );

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

	/**
	 * Attempt to authenticate a user using the given credentials.
	 *
	 * @param  array  $credentials
	 * @param  bool   $remember
	 * @return bool
	 */
	public function attempt(array $credentials = [], $remember = false)
	{
		$this->fireAttemptEvent($credentials, $remember);

		$students = $this->provider->retrieveAllByCredentials( $credentials );
		$user = null;

		if( $students->count() ) {
			foreach ( $students as $user ) {
				$this->lastAttempted = $user;

				if ($this->hasValidCredentials($user, $credentials)) {
					$this->login($user, $remember);

					// Store native Laravel pass hash, if not stored yet, so that later to remove the legacy column.
					$this->provider->newPassHash( $user, $credentials );

					return true;
				}
			}
		}

		// If the authentication attempt fails we will fire an event so that the user
		// may be notified of any suspicious attempts to access their account from
		// an unrecognized user. A developer may listen to this event as needed.
		$this->fireFailedEvent( $user, $credentials );

		return false;
	}

	/**
	 * Log a user into the application.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
	 * @param  bool  $remember
	 * @return void
	 */
	public function login(AuthenticatableContract $user, $remember = false)
	{
		parent::login( $user, $remember );
		$this->doLegacyLogin( $user, $this->user_type );
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
}