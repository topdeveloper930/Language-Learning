<?php

namespace Tests\Unit;


use App\Event;
use Tests\TestCase;

class EventTest extends TestCase
{

	/**
	 * @dataProvider providerGetCourseType
	 * @param int $event_id
	 * @param string $expected
	 */
	public function testGetCourseType( $event_id, $expected )
	{
		$event = Event::find($event_id);

		$this->assertSame( $expected, $event->getCourseType() );
	}

	public function providerGetCourseType()
	{
		return [
			[163067, 'Standard English'],
			[162974, 'Standard German'],
			[163079, 'Standard Russian'],
			[166996, 'Mexican Cooking Class']
		];
	}

	/**
	 * @dataProvider providerLengthHours
	 * @param $event_id
	 * @param $expected
	 */
	public function testLengthHours( $event_id, $expected )
	{
		$event = Event::find( $event_id );

		$this->assertEquals( $expected, $event->lengthHours() );
	}

	public function providerLengthHours()
	{
		return [
			[166702, 1],
			[166525, 1.25],
			[163729, 0.5]
		];
	}
}
