<?php

namespace Tests\Feature;

use Tests\TestCase;

class EventPolicyTest extends TestCase
{

	/**
	 * @dataProvider providerPolicy
	 * @param $user
	 * @param $event
	 * @param $action
	 * @param $expected
	 */
	public function testPolicy( $user, $event, $action, $expected )
	{
		$this->be( $user );

		if( $expected )
			$this->assertTrue( $user->can( $action, $event ) );
		else
			$this->assertFalse( $user->can( $action, $event ) );
	}

	public function providerPolicy()
	{
		$this->refreshApplication();
		$student = factory( \App\Student::class )->states('with_studentID')->make();
		$user = factory( \App\User::class )->make();
		$teacher = factory( \App\Teacher::class )->states('with_teacherID')->make();
		$admin = factory( \App\Member::class )->make();
		$inactive_admin = factory( \App\Member::class )->states('inactive')->make();

		$u_teacher = clone $user;
		$u_teacher->teacher = $teacher;

		$u_admin = clone $user;
		$u_admin->member = $admin;

		$u_admin_inactive = clone $user;
		$u_admin->$u_admin_inactive = $inactive_admin;

		return [
			[ $student, factory( \App\Event::class )->make([ 'studentID' => $student->studentID ]), 'delete', true ],
			[ $student, factory( \App\Event::class )->make([ 'studentID' => 1 ]), 'delete', false ],
			[ $u_teacher, factory( \App\Event::class )->make([ 'teacherID' => $u_teacher->teacher->teacherID ]), 'delete', true ],
			[ $u_teacher, factory( \App\Event::class )->make([ 'teacherID' => $u_teacher->teacher->teacherID + 1 ]), 'create', false ],
			[ $u_admin, factory( \App\Event::class )->make([ 'teacherID' => 212, 'studentID' => 212 ]), 'create', true ],
			[ $u_admin_inactive, factory( \App\Event::class )->make([ 'teacherID' => 212, 'studentID' => 212 ]), 'create', false ],
		];
	}
}
