<?php


namespace App\Services\Auth;


use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class AdminGuard extends LegacyGuard {

	protected $area         = 'admin';
	protected $user_type    = 'admin';
	protected $model_name   = 'member';

	protected $permitted_roles = [
		'super-admin', 'admin', 'hr-admin', 'marketer'
	];


	/**
	 * Check if the legacy user ID stored in SESSION and obtain the user by that ID.
	 *
	 * @return null|AuthenticatableContract
	 */
	public function getUserFromLegacySession()
	{
		if( $this->user_profile AND $this->user_profile->exists() )
			return $this->user_profile->usuario;

		if(
			!empty( $_SESSION[ 'userid' ] )
		    AND in_array( $_SESSION[ 'userType' ], $this->permitted_roles )
	        AND ( $model = $this->getProfileModel() ) instanceof UserType
		) {
			$this->user_profile = $model->find( $_SESSION['userid'] );

			if ( $this->user_profile )
				return $this->user_profile->usuario;
		}

		return null;
	}

}