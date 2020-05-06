<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StudentTest extends TestCase
{

	/**
	 * @dataProvider providerCreateWithAutoFill
	 * @param $data
	 */
    public function testCreateWithAutoFill( $data ) {
		$student = app(\App\Student::class )->createWithAutoFill( $data );

		$this->assertEquals( 'Inactive', $student->mailingList );
		$student->delete();
    }

	public function providerCreateWithAutoFill() {
//    	$faker = new \Faker\Generator;
    	return [
    		[[
    			'title' => 'Mr.',
			    'firstName' => 'Jane',
			    'lastName'  => 'Doe',
			    'remember_token' => NULL,
			    'password' => '12345'
    			]]
	    ];
	}
}
