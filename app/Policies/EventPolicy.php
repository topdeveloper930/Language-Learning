<?php

namespace App\Policies;

use App\Event;
use App\Services\Auth\UserType;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class EventPolicy extends BasePolicy
{

	/**
	 * Determine whether the user can view the event.
	 *
	 * @param  Authenticatable  $user
	 * @param  \App\Event $event
	 * @return mixed
	 */
	public function view(Authenticatable $user, Event $event)
	{
		return $this->delete( $user, $event );
	}

	/**
	 * Determine whether the user can create event.
	 *
	 * @param  Authenticatable  $user
	 * @return mixed
	 */
	public function create(Authenticatable $user, Event $event)
	{
		// Fake event is composed from the request data just for evaluation.
		return $this->delete( $user, $event );
	}

	/**
	 * Determine whether the user can update the event.
	 *
	 * @param  Authenticatable  $user
	 * @param  \App\Event $event
	 * @return mixed
	 */
	public function update(Authenticatable $user, Event $event)
	{
		return $this->delete( $user, $event );
	}

	/**
	 * Determine whether the user can delete the event.
	 *
	 * @param  Authenticatable  $user
	 * @param  \App\Event $event
	 * @return mixed
	 */
	public function delete(Authenticatable $user, Event $event)
	{
		if( $user instanceof UserType ) {
			switch ( $user->getType() )
			{
				case 'student':
					return $user->studentID == $event->studentID;
				case 'teacher':
					return $user->getInstance()->teacherID == $event->teacherID;
				case 'admin':
					return $user->getInstance() AND $user->getInstance()->active;
				case 'group': // TODO: not tested
					return false;
					return $user->group
					       AND $user->group->teachers->contains( $event->teacherID )
					       AND $user->group->students->contains( $event->studentID );
				default:
					return false;
			}
		}

		if( Auth::guard( 'api' )->check() )
			return $user->group AND
			       $user->group->students->contains($event->studentID) AND
			       $event->teacher->students->contains($event->studentID);

		return false;
	}
}
