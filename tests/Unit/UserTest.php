<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 23.03.2019
 * Time: 10:23
 */

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class UserTest extends TestCase {

	/**
	 * @dataProvider providerIsSuperAdmin
	 * @param $uid
	 * @param $expected
	 */
	public function testIsSuperAdmin($uid, $expected) {
		$user = User::find($uid);

		if($expected)
			$this->assertTrue($user->isSuperAdmin());
		else
			$this->assertFalse($user->isSuperAdmin());
	}

	public function providerIsSuperAdmin() {
		return [
			[1, true],
			[2, false],
			[306, false]
		];
	}

	/**
	 * @dataProvider providerGroupHasStudent
	 * @param $user_id
	 * @param $student_id
	 * @param $expected
	 */
	public function testGroupHasStudent($user_id, $student_id, $expected)
	{
		$user = User::find( $user_id );
		$group = $user->group;
		$exists = ($group AND $group->students->contains( $student_id ));

		if( $expected )
			$this->assertTrue($exists);
		else
			$this->assertFalse($exists);
	}

	public function providerGroupHasStudent()
	{
		return [
			[10, 19483, true],
			[10, 19439, false],
			[4, 19483, false]
		];
	}

	public function testGroupHistory()
	{
		$user = User::find( 10 );
		$this->assertTrue( $user->group_history->count() > 0 );
	}
}
