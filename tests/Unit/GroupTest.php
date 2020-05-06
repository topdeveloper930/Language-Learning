<?php

namespace Tests\Unit;

use \App\Group;
use App\User;
use Tests\TestCase;

class GroupTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAllStudents()
    {
	    $user = new User;
	    $user = $user->find( 10 );

	    $students = $user->group->getAllStudents();

	    $this->assertNotEmpty( $students );
	    $this->assertTrue( count( $students ) > 0 );
    }

    public function testTeachers()
    {
    	$group = Group::find(1);
    	$ss= $group->teachers;

	    foreach ( $ss as $s )
		    $this->assertNotEmpty($s->firstName);
    }

	public function testStudents()
	{
		$group = Group::find(1);
		$ss = $group->students;
		foreach ( $ss as $s )
			$this->assertNotEmpty($s->firstName);
	}
}
