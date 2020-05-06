<?php

namespace App\Policies;

use App\User;
use App\Teacher;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class TeacherPolicy extends BasePolicy
{
	public function before( Authenticatable $user, $ability )
	{
		if ( parent::before( $user, $ability ) OR ( $user->member AND ( 'admin' == $user->member->userType OR 'hr-admin' == $user->member->userType ) ) )
			return true;
	}

    /**
     * Determine whether the user can view the teacher.
     *
     * @param  \App\User    $user
     * @param  \App\Teacher $teacher
     *
     * @return mixed
     */
    public function view(Authenticatable $user, Teacher $teacher)
    {
    	$guard = Auth::guard();

    	if( method_exists( $guard, 'userType' ) ) {
		    switch ( $guard->userType() )
		    {
			    case 'student':
				    return $user->teachers->contains( $teacher->teacherID );
			    case 'teacher':
				    return $user->teacher->teacherID == $teacher->teacherID;
			    case 'admin':
				    return $user->member->exists();
			    case 'group': // TODO: not tested
				    return $user->group->teachers->contains( $teacher->teacherID );
			    default:
				    return false;
		    }
	    }

    	if( Auth::guard( 'api' )->check() )
		    return $guard->user()->group AND $guard->user()->group->teachers->contains($teacher->teacherID);

    	return false;
    }

    /**
     * Determine whether the user can create teachers.
     * Super admin can. And that is handled in the BasePolicy class.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
	    return false;
    }

    /**
     * Determine whether the user can update the teacher.
     * Save super admin and admin, only the teacher can update own profile.
     *
     * @param  \App\User  $user
     * @param  \App\Teacher  $teacher
     * @return mixed
     */
    public function update(User $user, Teacher $teacher)
    {
	    return $user->hasAdminPermit()
	           OR ( Teacher::TYPE == $user->getType() AND $user->getPrimaryKey() == $teacher->getKey() );
    }

    /**
     * Determine whether the user can delete the teacher.
     * Nobody can save super admin.
     *
     * @param  \App\User  $user
     * @param  \App\Teacher $teacher
     * @return mixed
     */
    public function delete(User $user, Teacher $teacher)
    {
    	return false;
    }
}
