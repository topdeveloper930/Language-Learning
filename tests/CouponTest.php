<?php

namespace Tests\Unit;

use App\Coupon;
use Tests\TestCase;

class CouponTest extends TestCase {

	/**
	 * @dataProvider providerCheck_coupon
	 */
	public function testStudentCanUse( $data, $expected ) {
		if( $expected )
			$this->assertTrue( Coupon::studentCanUse( $data[ 'code' ], $data[ 'student_id' ] ), sprintf( 'Coupon %s not valid for student %d', $data[ 'code' ], $data[ 'student_id' ] ) );
		else
			$this->assertFalse( Coupon::studentCanUse( $data[ 'code' ], $data[ 'student_id' ] ) );
	}

	public function providerCheck_coupon() {
		return [
			[['code' => 'SFIP3D6J', 'student_id' => 19483 ], true],
			[['code' => 'TEST-FE63LUDF', 'student_id' => 19505 ], false],
			[['code' => 'RP-IIHOR1224', 'student_id' => 21610 ], true],
			[['code' => 'RP-IIHOR1224', 'student_id' => 19492 ], false]     // own referral coupon
		];
	}
}
