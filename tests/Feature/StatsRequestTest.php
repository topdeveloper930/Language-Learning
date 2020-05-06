<?php

namespace Tests\Feature;

use Tests\TestCase;

class StatsRequestTest extends TestCase
{

	public function testStatsRequest()
	{
		$user = $this->app->make(\App\User::class)->find(1);
		$this->be( $user );
		$stats_request = $this->app->make( \App\Http\Requests\StudentAveragePaymentRequest::class );

		$this->assertTrue( $stats_request->authorize() );
	}

	/**
	 * @dataProvider providerStatsRequestException
	 * @expectedException \Illuminate\Auth\Access\AuthorizationException
	 */
	public function testStatsRequestException( $type, $id )
	{
		$user = $this->app->make($type)->find( $id );
		$this->be( $user );
		$this->app->make( \App\Http\Requests\StudentAveragePaymentRequest::class );
	}

	public function providerStatsRequestException()
	{
		return [
			[ \App\User::class, 15 ],       // hr-admin
			[ \App\User::class, 305 ],      // teacher
			[ \App\Student::class, 14983 ]  // student
		];
	}
}
