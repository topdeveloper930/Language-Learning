<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ValidTest extends TestCase {

	/**
	 * @dataProvider providerPhone
	 * @param $phone
	 * @param $expected
	 */
	public function testPhone( $phone, $expected ) {
		$validator = Validator::make(['phone' => $phone], [
			'phone' => 'phone'
		]);

		if( $expected ){
			$this->assertFalse( $validator->fails(), "$phone - is not valid phone number" );
		}
		else{
			$this->assertTrue( $validator->fails(), "$phone - is valid phone number"  );
			$this->assertEquals( __('validation.phone', ['attribute' => 'telephone']), $validator->errors()->first() );
		}
	}

	/**
	 * @dataProvider providerTimezone
	 * @param      $expected
	 * @param      $tz
	 */
	public function testTimezone( $expected, $tz )
	{
		$validator = Validator::make(['timezone' => $tz], [
			'timezone' => 'timezone'
		]);

		if( $expected )
			$this->assertFalse( $validator->fails(), "$tz - is not valid timezone number" );
		else {
			$this->assertTrue( $validator->fails() );
			$this->assertEquals( __('validation.timezone', ['attribute' => 'time zone']), $validator->errors()->first() );
		}
	}

	public function providerTimezone() {
		return [
			[ true, 'Europe/Kiev' ],
			[ false, 'Something' ],
			[ true, '' ],
			[ false, 646 ],
			[ false, [646] ],
			[ false, true ],
			[ true, 'America/Denver' ],
			[ true, 'America/Argentina/Buenos_Aires' ]
		];
	}

	public function providerPhone() {
		return [
			[ 'some string', false ],
			[ 5555555555, true ],
			[ '+22 555 555 1234', true ],
			[ '(607) 555 1234', true ],
			[ '(022607) 555 1234', true ],
			[ '+22 555 555 1234 ext 567', false ],
			[ '', true ],
			[ '+1555 ex 1234', false ],
			[ '+380974693697', true ],
			[ '+1 (781) 519-9984', true ]
		];
	}
}
