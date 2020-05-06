<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 05.09.2019
 * Time: 14:51
 */

namespace Tests\Unit;

use Tests\TestCase;

class UserTypeTest extends TestCase {

	/**
	 * @dataProvider providerHasAdminPermit
	 * @param string $user_type
	 * @param $expected
	 */
	public function testHasAdminPermit($user_type, $id, $expected) {
		if($expected)
			$this->assertTrue($user_type::find( $id )->hasAdminPermit());
		else
			$this->assertFalse($user_type::find( $id )->hasAdminPermit());
	}

	/**
	 * @dataProvider providerIsMember
	 * @param string $user_type
	 * @param $expected
	 */
	public function testIsMember($user_type, $id, $expected) {
		if($expected)
			$this->assertTrue($user_type::find( $id )->isMember());
		else
			$this->assertFalse($user_type::find( $id )->isMember());
	}

	public function providerHasAdminPermit() {
		return [
			[ \App\User::class, 1, true ],
			[ \App\User::class, 2, true ],
			[ \App\User::class, 5, false ],
			[ \App\User::class, 306, false ],
			[ \App\Student::class, 19483, false ],
			[ \App\Member::class, 1722, true ],
			[ \App\Member::class, 1756, false ],
			[ \App\Teacher::class, 191, false ],
			[ \App\Group::class, 1, false ]
		];
	}

	public function providerIsMember() {
		return [
			[ \App\User::class, 1, true ],
			[ \App\User::class, 2, true ],
			[ \App\User::class, 5, true ],
			[ \App\User::class, 306, false ],
			[ \App\Student::class, 19483, false ],
			[ \App\Member::class, 1722, true ],
			[ \App\Member::class, 1756, true ],
			[ \App\Teacher::class, 191, false ],
			[ \App\Group::class, 1, false ]
		];
	}
}
