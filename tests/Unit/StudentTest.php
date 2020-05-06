<?php

namespace Tests\Unit;

use App\Student;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StudentTest extends TestCase
{
	/**
	 * @var Student
	 */
	private $student;

	protected function setUp() {
		parent::setUp();

		$this->student = new Student;
	}

	/**
     * @dataProvider providerBelongsToGroupOfUser
     * @return void
     */
	public function testBelongsToGroupOfUser($sid, $gid, $expected)
	{
		$student = $this->student->find( $sid );

		$this->assertSame( $expected, $student->belongsToGroupOfUser( $gid ) );
	}

	public function providerBelongsToGroupOfUser()
	{
		return [
			[19483, 10, true],
			[19483, 3, false],
			[19571, 10, false],
			[19571, 1, false]
		];
	}

	public function testGroupHistory()
	{
		$student = $this->student->find( 19483 );

		$this->assertSame(true, $student->group_history()->where('usuario_id', 10 )->exists() );
	}

	public function testGroupHistoryFalse()
	{
		$student = $this->student->find( 19483 );

		$this->assertSame(false, $student->group_history()->where('usuario_id', 4 )->exists() );
	}


	/**
	 * @dataProvider providerTeachers
	 * @param $teacher_id
	 * @param $student_id
	 * @param $expected
	 */
	public function testTeachers( $teacher_id, $student_id, $expected )
	{
		$student = Student::find($student_id);

		$this->assertSame( $expected, $student->teachers->contains( $teacher_id ) );

		$this->assertSame(
			trim( sprintf( '%s %s %s', $student->title, $student->firstName, $student->lastName ) ),
			$student->accost()
		);
	}

	public function providerTeachers()
	{
		return [
			[191, 19483, true],
			[191, 21588, false]
		];
	}
}
