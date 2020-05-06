<?php

namespace Tests\Unit;


use App\Teacher;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGroup()
    {
	    $teacher = Teacher::find(191);
	    $this->assertEquals(1, $teacher->group()->where('usuario_id', 10)->get()[0]->groupID);
    }

    public function testAddTeacherToGroup()
    {
	    $this->assertTrue( \App\Group::find(1)->teachers()->where('teacherID', 12)->count() == 0 );

    	$teacher = \App\Teacher::find(12);

	    $teacher->group()->attach(1);

	    $this->assertTrue( \App\Group::find(1)->teachers()->where('teacherID', 12)->count() > 0 );
    }

	/**
	 * @depends testAddTeacherToGroup
	 */
	public function testRemoveTeacherFromGroup()
	{
		$this->assertTrue( \App\Group::find(1)->teachers()->where('teacherID', 12)->count() > 0 );

		$teacher = \App\Teacher::find(12);

		$teacher->group()->detach(1);

		$this->assertTrue( \App\Group::find(1)->teachers()->where('teacherID', 12)->count() == 0 );
	}

	/**
	 * @dataProvider providerStudents
	 * @param $teacher_id
	 * @param $student_id
	 * @param $expected
	 */
	public function testStudents( $teacher_id, $student_id, $expected )
	{
		$teacher = Teacher::find($teacher_id);

		$this->assertSame( $expected, $teacher->students->contains( $student_id ) );
	}

	public function providerStudents()
	{
		return [
			[191, 19483, true],
			[191, 21588, false]
		];
	}
}
