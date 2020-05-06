<?php

namespace App\Http\Controllers\API;

use App\Event;
use App\Http\Controllers\APIController;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\EventRequest;
use App\Http\Requests\StudentRequest;
use App\Http\Requests\TeacherViewRequest;

/**
 * @apiDefine ClassResponse
 * @apiSuccess (Student object) {number} calendarID The class identifier.
 * @apiSuccess (Student object) {number} teacherID The teacher ID.
 * @apiSuccess (Student object) {number} studentID The student ID.
 * @apiSuccess (Student object) {string} entryTitle The class title, containing the student name and the course type.
 * @apiSuccess (Student object) {string} entryType Type of the event (always "Student" for classes)
 * @apiSuccess (Student object) {string} eventStart Class beginning - UTC date and time string
 * @apiSuccess (Student object) {string} eventEnd Class end - UTC date and time string
 * @apiSuccess (Student object) {string} active Status of the class: 1 - for active class; 0 - cancelled.
 */

class ClassController extends APIController
{
	public function index()
	{
		return $this->notImplemented();
	}

	/**
	 * @api {get} /api/student/:student/classes View Student Classes
	 * @apiName StudentClasses
	 * @apiGroup Student
	 * @apiDescription View all classes of the student.
	 *
	 * @apiParam  (URI) {number} student Student ID (studentID)
	 *
	 * @apiSuccess (Success 200) {object[]} response Array of class objects.
	 * @apiUse ClassResponse
	 * @apiSuccessExample {json} Success-Response:
	 *     HTTP/1.1 200 OK
	 *     Content-Type: application/json
	 *
	 *     [
	 *       {
	 *         "calendarID": 167012,
	 *         "teacherID": 191,
	 *         "studentID": 21630,
	 *         "entryTitle": "Mrs. Janet Doe (Standard English)",
	 *         "entryType": "Student",
	 *         "eventStart": "2019-02-26 12:00:00",
	 *         "eventEnd": "2019-02-26 14:30:00",
	 *         "active": 0
	 *       },
	 *       {
	 *         "calendarID": 163536,
	 *         "teacherID": 191,
	 *         "studentID": 21630,
	 *         "entryTitle": "Mrs. Janet Doe (Standard English)",
	 *         "entryType": "Student",
	 *         "eventStart": "2018-08-05 16:00:00",
	 *         "eventEnd": "2018-08-05 17:00:00",
	 *         "active": 1
	 *       },
	 *     ]
	 *
	 * @apiUse NoUserPermit
	 */
	/**
	 * TODO: filter classes: active|cancelled; passed|scheduled etc.
	 * @param StudentRequest $request
	 *
	 * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
	 */
	public function studentClasses( StudentRequest $request )
	{
		return response( $request->inst->classes );
	}

	/**
	 * @api {get} /api/teacher/:teacher/classes View Teacher Classes
	 * @apiName TeacherClasses
	 * @apiGroup Teacher
	 * @apiDescription View all classes of the teacher.
	 *
	 * @apiParam  (URI) {number} id Teacher's ID (teacherID)
	 *
	 * @apiSuccess (Success 200) {object[]} response Array of class objects.
	 * @apiUse ClassResponse
	 * @apiSuccessExample {json} Success-Response:
	 *     HTTP/1.1 200 OK
	 *     Content-Type: application/json
	 *
	 *     [
	 *       {
	 *         "calendarID": 167012,
	 *         "teacherID": 191,
	 *         "studentID": 21630,
	 *         "entryTitle": "Mrs. Janet Doe (Standard English)",
	 *         "entryType": "Student",
	 *         "eventStart": "2019-02-26 12:00:00",
	 *         "eventEnd": "2019-02-26 14:30:00",
	 *         "active": 0
	 *       },
	 *       {
	 *         "calendarID": 163536,
	 *         "teacherID": 191,
	 *         "studentID": 21630,
	 *         "entryTitle": "Mrs. Janet Doe (Standard English)",
	 *         "entryType": "Student",
	 *         "eventStart": "2018-08-05 16:00:00",
	 *         "eventEnd": "2018-08-05 17:00:00",
	 *         "active": 1
	 *       },
	 *     ]
	 *
	 * @apiUse NoUserPermit
	 */
	public function teacherClasses( TeacherViewRequest $request )
	{
		return response( $request->teacher->classes );
	}

	/**
	 * @api {post} /api/class Create Class
	 * @apiName CreateClass
	 * @apiGroup Class
	 * @apiDescription Create Class. <code>Attention!</code> The Live Lingua calendar requires at least 24 hour notice to schedule, cancel or make changes to classes. This is to give enough notice to the teachers so they can prepare the class.
	 *
	 * @apiParam (Parameters) {number} teacherID The teacher identifier
	 * @apiParam (Parameters) {number} studentID The student identifier
	 * @apiParam (Parameters) {string} eventStart Class start time (UTC). Must be valid DateTime string in format "Y-m-d H:i:s".
	 * @apiParam (Parameters) {string} eventEnd Class end time (UTC). Must be valid DateTime string in format "Y-m-d H:i:s".
	 * @apiParam (Parameters) {string} course Course type that class scheduled for. Must be valid Live Lingua courseType.
	 *
	 * @apiParamExample {json} Request-Example:
	 * POST /api/class
	 * Accept: application/json
	 * Content-Type: application/json
	 * Authorization: Bearer SOME_TOKEN_STRING_HERE
	 * {
	 *    "teacherID": 191,
	 *    "studentID": 21630,
	 *    "eventStart": "2019-05-09 14:00:00",
	 *    "eventEnd": "2019-05-09 15:00:00",
	 *    "course": "Standard English"
	 * }
	 *
	 * @apiSuccess (Created 201) {Object} class Newly created class instance.
	 * @apiSuccess (Response){json} body Class object
	 * @apiUse ClassResponse
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 201 Created
	 * Content-Type: application/json
	 *
	 * {
	 *    "calendarID": 167777,
	 *    "teacherID": 191,
	 *    "studentID": 21630,
	 *    "entryTitle": "Mrs. Janet Doe (Standard English)",
	 *    "entryType": "Student",
	 *    "eventStart": "2019-05-09 14:00:00",
	 *    "eventEnd": "2019-05-09 15:00:00",
	 *    "active": "Active"
	 * }
	 *
	 * @apiError (422) {json} UnprocessableEntity Data validation error(s)
	 * @apiErrorExample {json}  Error-Response:
	 * HTTP/1.1 422 Unprocessable Entity
	 * Content-Type: application/json
	 * {
	 *     "eventStart": ["The class must have 24 hrs notice period."],
	 *     "eventEnd": ["The event end must be a date after event start."],
	 *     "student": ["The student has not enough credits."]
	 * }
	 */
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  CreateEventRequest  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(CreateEventRequest $request)
	{
		$cls = Event::create( $request->all() );

		//TODO:
		// 4) send notifications - queue task?
		// 5) sync to Google calendar (teacher) - queue task?

		return response( $cls, 201 );
	}

	/**
	 * @api {get} /api/class/:class Get class by ID
	 * @apiName GetClass
	 * @apiGroup Class
	 * @apiDescription Returns an objects in json format. The object represents basic information on the class.
	 *
	 * @apiParam (URI) {number} class URI parameter class ID (calendarID in the system)
	 *
	 * @apiSuccess (Response){json} body Class object
	 * @apiUse ClassResponse
	 *
	 * @apiSuccessExample {json} Success-Response:
	 *     HTTP/1.1 200 OK
	 *     Content-Type: application/json
	 *
	 *     {
	 *       "calendarID": 167587,
	 *       "teacherID": 191,
	 *       "studentID": 19483,
	 *       "entryTitle": "Mrs. Janet Doe (Standard English)",
	 *       "entryType": "Student",
	 *       "eventStart": "2019-03-09 07:00:00",
	 *       "eventEnd": "2019-03-09 08:00:00",
	 *       "active": "Active"
	 *     }
	 *
	 * @apiUse NoUserPermit
	 */
	/**
	 * Display the specified resource.
	 *
	 * @param  EventRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function show( EventRequest $request )
	{
		return response( $request->inst );
	}

	/**
	 * @api {put | patch} /api/class/:class Reschedule Class
	 * @apiName RescheduleClass
	 * @apiGroup Class
	 * @apiDescription Change class time. The Live Lingua calendar requires at least 24 hour notice to schedule, cancel or make changes to classes. This is to give enough notice to the teachers so they can prepare the class.
	 *
	 * @apiParam (URI) {number} class URI parameter class ID (calendarID in the system)
	 * @apiParam (Parameters) {string} eventStart New class UTC start time. Must be valid DateTime string in format "Y-m-d H:i:s".
	 * @apiParam (Parameters) {string} eventEnd New class UTC end time. Must be valid DateTime string in format "Y-m-d H:i:s".
	 *
	 * @apiParamExample {json} Request-Example:
	 * PUT /api/class/167587
	 * Accept: application/json
	 * Content-Type: application/json
	 * Authorization: Bearer SOME_TOKEN_STRING_HERE
	 * {
	 *    "eventStart": "2019-03-09 07:00:00",
	 *    "eventEnd": "2019-03-09 08:00:00"
	 * }
	 *
	 * @apiSuccess (Response){json} body Class object
	 * @apiUse ClassResponse
	 *
	 * @apiSuccessExample {json} Success-Response:
	 *     HTTP/1.1 202 Accepted
	 *     Content-Type: application/json
	 *
	 *     {
	 *       "calendarID": 167587,
	 *       "teacherID": 191,
	 *       "studentID": 19483,
	 *       "entryTitle": "Mrs. Janet Doe (Standard English)",
	 *       "entryType": "Student",
	 *       "eventStart": "2019-03-09 07:00:00",
	 *       "eventEnd": "2019-03-09 08:00:00",
	 *       "active": "Active"
	 *     }
	 *
	 * @apiError (422) {json} UnprocessableEntity Data validation error(s)
	 * @apiErrorExample {json}  Error-Response:
	 * HTTP/1.1 422 Unprocessable Entity
	 * Content-Type: application/json
	 * {
	 *     "eventStart": ["The class must have 24 hrs notice period."],
	 *     "eventEnd": ["The event end must be a date after event start."],
	 * }
	 */
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  EventRequest $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update( EventRequest $request )
	{
		// Update the class
		$request->inst->eventStart = $request->eventStart;
		$request->inst->eventEnd = $request->eventEnd;

		$request->inst->save();

		//TODO:
		// 1) send notifications - queue task?
		// 2) sync to Google calendar (teacher) - queue task?

		return response( $request->inst, 202 );
	}

	/**
	 * @api {delete} /api/class/:class Cancel Class
	 * @apiName CancelClass
	 * @apiGroup Class
	 * @apiDescription Cancels class. The Live Lingua calendar requires at least 24 hour notice to cancel class.
	 *
	 * @apiParam (URI) {number} class URI parameter class ID (calendarID in the system)
	 *
	 * @apiSuccess (Success 202) {number} body 1 - means that the class was successfully cancelled.
	 * @apiSuccessExample {html} Success-Response:
	 * HTTP/1.1 202 Accepted
	 * Content-Type: text/html; charset=UTF-8
	 *
	 * 1
	 *
	 * @apiUse NoUserPermit
	 *
	 * @apiUse NotFound
	 *
	 * @apiError (422) {json} UnprocessableEntity Data validation error(s)
	 * @apiErrorExample {json}  Error-Response:
	 * HTTP/1.1 422 Unprocessable Entity
	 * Content-Type: application/json
	 * {
	 *     "eventStart": ["The class must have 24 hrs notice period."]
	 * }
	 */
	/**
	 * Remove the specified resource from group:
	 *  set student Inactive, remove from group, write log to group_history.
	 *
	 * @param  EventRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( EventRequest $request )
	{
		$request->inst->active = 0;

		try{
//			throw new \RuntimeException('test error trigger');
			//TODO:
			// 1) Send notification(s)
			// 2) Trigger sync to teacher's calendar(consider using existing CLI?)
			$request->inst->save();
			return response( 1, 202 );
		}
		catch ( \Exception $e ) {
			return $this->serverError( $e );
		}
	}
}
