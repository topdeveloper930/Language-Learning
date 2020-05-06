<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\APIController;
use App\Http\Requests\TeacherViewRequest;
use App\Unavailability;

class TeacherUnavailabilityController extends APIController
{
	/**
	 * @api {get} /api//teacher/:id/unavailability/:tz/:tz_2 Unavailability
	 * @apiName TeacherUnavailability
	 * @apiGroup Teacher
	 * @apiDescription Teacher's unavailability periods.<br>
	 * Time zone is optional parameter that can be combined of two parts (America/New_York, Asia/Hong_Kong) or just one (GMT, +04:30, -07:00).<br>
	 * Omit for UTC time zone use.
	 *
	 * @apiParam  (URI) {number} id teacherID - unique teacher ID
	 * @apiParam  (URI) {string} [tz="UTC"] First part of time zone. It can be the only time zone part, if second part (after the slash) makes valid time zone designation (like GMT or +02:00).
	 * @apiParam  (URI) {string} [tz_2] Second part of time zone. Together with first part it must make a valid time zone.
	 *
	 * @apiSuccess (200){json} response array of unavailability objects.
	 *
	 * @apiSuccess (Unavailability object) {number} teacherID Teacher's unique identifier.
	 * @apiSuccess (Unavailability object) {array} day Days of week that the unavailability timespan is for: O - Sunday, 1 - Monday etc.
	 * @apiSuccess (Unavailability object) {string} startTime Beginning of the unavailability period.
	 * @apiSuccess (Unavailability object) {string} endTime End of the unavailability period.
	 *
	 * @apiSuccessExample {json} Success-Response:
	 *     HTTP/1.1 200 OK
	 *     Content-Type: application/json
	 *
	 *     [
	 *       {
	 *         "teacherID": 191,
	 *         "day": [
	 *           0,
	 *           1,
	 *           2,
	 *           3,
	 *           4,
	 *           5,
	 *           6
	 *         ],
	 *         "startTime": "00:00:00",
	 *         "endTime": "05:00:00"
	 *       },
	 *       {
	 *         "teacherID": 191,
	 *         "day": [
	 *           0,
	 *           1,
	 *           2,
	 *           3,
	 *           4,
	 *           5,
	 *           6
	 *         ],
	 *         "startTime": "15:00:00",
	 *         "endTime": "24:00:00"
	 *       }
	 *     ]
	 *
	 * @apiUse NoUserPermit
	 */
	/**
	 * Display the specified resource.
	 *
	 * @param  TeacherViewRequest $request
	 * @return \Illuminate\Http\Response
	 */
	public function show( TeacherViewRequest $request )
	{
		$unavailability = new Unavailability;

		try {
			return response( $unavailability->ofTeacher( $request->teacher->teacherID, $request->teacher->timezone_code(), $request->tz ) );
		}
		catch ( \Exception $e ) {
			return $this->serverError( $e );
		}
	}
}
