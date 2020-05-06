<?php

namespace App\Http\Controllers\API;

use App\ClassCreditsLog;
use App\CourseList;
use App\Http\Controllers\APIController;
use App\Http\Requests\AddCreditRequest;
use App\Http\Requests\CreditRequest;
use App\Student;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditCotroller extends APIController
{
	/**
	 * @api {get} /api/student/:student/credit/:course Credit View
	 * @apiName ViewCredit
	 * @apiGroup Student
	 * @apiDescription View available credits of the student for the course.
	 *
	 * @apiParam (URI) {number} student The student identifier (studentID)
	 * @apiParam (URI) {string} course The course type that the remaining credits requested for.
	 *
	 * @apiSuccess (OK 200) {number} credit The amount of the remaining hours, which is calculated by summing all credits minus classes taken and scheduled.
	 * @apiSuccessExample {html} Success-Response:
	 * HTTP/1.1 200 OK
	 * Content-Type: text/html; charset=UTF-8
	 *
	 * 7
	 *
	 * @apiError (422) {json} UnprocessableEntity Data validation error
	 * @apiErrorExample {json}  Error-Response:
	 * HTTP/1.1 422 Unprocessable Entity
	 * Content-Type: application/json
	 * {
	 *     "course": ["The selected course is invalid."]
	 * }
	 *
	 */
	/**
	 * Show available credits of the student for the course.
	 *
	 * @param  CreditRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function show( CreditRequest $request )
	{
		return response( $request->inst->remainingCredits( $request->course ) );
	}

	/**
	 * @api {post} /api/student/:student/credit Credit Add
	 * @apiName AddCredit
	 * @apiGroup Student
	 * @apiDescription Add Credit to student's account.
	 *
	 * @apiParam (URI) {number} student The student identifier (studentID)
	 *
	 * @apiParam (Parameters) {number} hours Number of hours credited. Float.
	 * @apiParam (Parameters) {string} course Course type to be credited. Must be valid Live Lingua courseType.
	 *
	 * @apiParamExample {json} Request-Example:
	 * POST /api/student/21630
	 * Accept: application/json
	 * Content-Type: application/json
	 * Authorization: Bearer SOME_TOKEN_STRING_HERE
	 * {
	 *    "hours": 7.5,
	 *    "course": "Standard English"
	 * }
	 *
	 * @apiSuccess (Created 201) {Object} classCreditsLog The record instance.
	 * @apiSuccess (Class Credit Record) {number} transactionID The transaction identifier.
	 * @apiSuccess (Class Credit Record) {string} process The type of the operation: hoursAdded - for crediting an account.
	 * @apiSuccess (Class Credit Record) {string} language The course language. Determined out of the course given.
	 * @apiSuccess (Class Credit Record) {string} course The course credited.
	 * @apiSuccess (Class Credit Record) {number} hours The amount of hours credited.
	 * @apiSuccess (Class Credit Record) {number} studentID The student ID.
	 * @apiSuccess (Class Credit Record) {number} classCreditsLogID The record identifier.
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 201 Created
	 * Content-Type: application/json
	 *
	 * {
	 *    "transactionID": 22696,
	 *    "process": "hoursAdded",
	 *    "language": "English",
	 *    "course": "Standard English",
	 *    "hours": 7.5,
	 *    "studentID": 21630,
	 *    "classCreditsLogID": 12884
	 * }
	 *
	 * @apiError (422) {json} UnprocessableEntity Data validation error(s)
	 * @apiErrorExample {json}  Error-Response:
	 * HTTP/1.1 422 Unprocessable Entity
	 * Content-Type: application/json
	 * {
	 *     "course": ["The selected course is invalid."],
	 *     "hours": ["The hours must be a number."]
	 * }
	 */
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  AddCreditRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function store( AddCreditRequest $request )
	{
		$course = CourseList::where( 'courseType', $request->course )->first();

		$transaction = Transaction::via_API( $request->inst->studentID, $course->getCourseTitle(), $request->hours );

		$creditLog = new ClassCreditsLog([
			'transactionID' => $transaction->transactionID,
			'process'       => 'hoursAdded',
			'language'      => $course->language,
			'course'        => $request->course,
			'numStudents'   => 1,
			'hours'         => $request->hours,
			'notes'         => ''

		]);

		$res = $request->inst->classCreditLogs()->save( $creditLog );

		//TODO:
		// 4) send notifications - queue task?

		return response( $res, 201 );
	}
}
