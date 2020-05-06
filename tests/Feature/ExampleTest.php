<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
	protected function setUp()
	{
		$this->markTestSkipped('Dev purpose test class');
	}

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/student/19483', ['Accept' => 'application/json', 'Authorization' => 'Bearer 7775']);

        $response->assertStatus(200);
    }

	/**
	 * An endpoint access test.
	 *
	 * @dataProvider providerResponseStatus
	 * @return void
	 */
	public function testResponseStatus( $status_expected, $method, $uri, $headers = [], $data = []  )
	{
		$response = $this->json($method, $uri, $data, $headers);

		$response->assertStatus($status_expected);
	}

	public function providerResponseStatus()
	{
		return [
			[403, 'GET', '/student/19439', ['Accept' => 'application/json', 'Authorization' => 'Bearer 7775']], // Wrong group (or no group) student
			[200, 'GET', '/student/19483', ['Accept' => 'application/json', 'Authorization' => 'Bearer 7775']], // Accessible student
			[403, 'GET', '/student/19483', ['Accept' => 'application/json', 'Authorization' => 'Bearer 7774']], // User is not the group master
			[422, 'POST', '/student', ['Accept' => 'application/json', 'Authorization' => 'Bearer 7775'], ["title" => "Ms."]], // Not valid data
			[422, 'PUT', '/student/19483', ['Accept' => 'application/json', 'Authorization' => 'Bearer 7775'], ["email" => "new@email.com"]] // Email taken
		];
	}

	/**
	 * API endpoins tests
	 */
	public function testNonAuthenticatedUser()
	{
		$response = $this->json('GET', '/teacher/email/papajini%40gmail.com');

		$response->assertStatus(401);
	}

	public function testNotValidEmail()
	{
		$response = $this
			->json('GET', '/teacher/email/papajinimail', [], ['Authorization' => 'Bearer 7775']);

		$response->assertStatus(422);
	}

	public function testNotAuthorized()
	{
		$response = $this
			->json('GET', '/teacher/email/oceana.castaneda@gmail.com', [], ['Authorization' => 'Bearer 7775']);

		$response->assertStatus(403);
	}

	public function testNotAuthorizedUser()
	{
		$response = $this
			->json('GET', '/teacher/email/papajini@gmail.com', [], ['Authorization' => 'Bearer 8888']);

		$response->assertStatus(403);
	}

	public function testSuperAdminAccess()
	{
		$response = $this
			->json('GET', '/teacher/email/papajini@gmail.com', [], ['Authorization' => 'Bearer 7777']);

		$response->assertStatus(200);
		$response->assertJsonFragment(['teacherID' => 191]);
	}

	public function testOK()
	{
		$response = $this
			->json('GET', '/teacher/email/papajini@gmail.com', [], ['Authorization' => 'Bearer 7775']);

		$response->assertStatus(200);
		$response->assertJsonFragment(['teacherID' => 191]);
	}
}
