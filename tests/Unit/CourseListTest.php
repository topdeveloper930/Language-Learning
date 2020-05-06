<?php

namespace Tests\Unit;


use App\CourseList;
use Tests\TestCase;

class CourseListTest extends TestCase
{
	/**
	 * @dataProvider providerGetCourseTitle
	 * @param $event_id
	 * @param $expected
	 */
	public function testGetCourseTitle( $courseType, $expected )
	{
		$cl = CourseList::where( [ 'courseType' => $courseType ] )->first();

		$this->assertEquals( $expected, $cl->getCourseTitle() );
	}

	public function providerGetCourseTitle()
	{
		return [
			['Spanish for Business', 'Spanish-Spanish for Business'],
			['Standard English', 'English-Standard English'],
			['KET', 'English-KET']
		];
	}
}
