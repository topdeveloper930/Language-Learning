<?php

namespace App\Policies;

use App\Group;
use App\Services\Auth\UserType;
use App\Student;

class StudentPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view the student.
     *
     * @param  UserType $user
     * @param  \App\Student  $student
     * @return mixed
     */
    public function view(UserType $user, Student $student)
    {
	    return $this->delete( $user, $student );
    }

    /**
     * Determine whether the user can create students.
     *
     * @param  UserType  $user
     * @return mixed
     */
    public function create(UserType $user)
    {
        // Check if the user linked to a group. If so, then (s)he can create students under this group.
	    return $user->hasAdminPermit() OR Group::TYPE == $user->getType();
    }

    /**
     * Determine whether the user can update the student.
     *
     * @param  \App\User  $user
     * @param  \App\Student  $student
     * @return mixed
     */
    public function update(UserType $user, Student $student)
    {
	    return ( Student::TYPE == $user->getType() AND $user->getPrimaryKey() == $student->getKey() )
	           OR $this->delete( $user, $student );
    }

    /**
     * Determine whether the user can delete the student.
     *
     * @param  UserType $user
     * @param  \App\Student  $student
     * @return mixed
     */
    public function delete(UserType $user, Student $student)
    {
    	return $user->hasAdminPermit()
	           OR ( Group::TYPE == $user->getType() AND $user->group->students->contains( $student->getKey() ) );
    }
}
