<?php

namespace App\Policies;

use App\CalendarExternal;
use App\Services\Auth\UserType;
use \Illuminate\Contracts\Auth\Authenticatable;

class CalendarExternalPolicy extends BasePolicy
{
	/**
	 * Determine whether the user can view the calendar (as well as delete or update).
	 * User can view (delete or update) only own calendar integration.
	 *
	 * @param \Illuminate\Contracts\Auth\Authenticatable $user
	 * @param  \App\CalendarExternal                     $cal
	 *
	 * @return mixed
	 */
	public function view( Authenticatable $user, CalendarExternal $cal )
	{
		if( $user instanceof UserType ) // Student
			return strcmp($user->getType(), $cal->user_type ) === 0 AND $user->{$user->getKeyName()} == $cal->user_id;

		return 'teacher' == $cal->user_type AND $user->teacher AND $cal->user_id == $user->teacher->teacherID;
	}

	public function update( Authenticatable $user, CalendarExternal $cal )
	{
		return $this->view( $user, $cal );
	}

	public function delete( Authenticatable $user, CalendarExternal $cal )
	{
		return $this->view( $user, $cal );
	}

	public function create( Authenticatable $user, CalendarExternal $cal )
	{
//		return true;
		return ( $user instanceof UserType AND 'student' == $user->getType() ) OR !is_null( $user->teacher );
	}
}
