<?php

namespace App\Http\Controllers\API;

use App\Group;
use App\Http\Controllers\APIController;
use App\Http\Requests\TeacherGroupRequest;
use App\Http\Requests\TeacherViewRequest;
use App\Jobs\GetGender;
use App\Teacher;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends APIController
{
	/**
	 * @api {get} /api/teacher View all
	 * @apiName GetTeachers
	 * @apiGroup Teacher
	 * @apiDescription The endpoint returns array of objects in json format.
	 * Every object represents base information on the teacher available.
	 *
	 * @apiParam void No parameters expected
	 *
	 * @apiSuccess (200){json} response array of objects.
	 * @apiSuccessExample {json} Success-Response:
	 *     HTTP/1.1 200 OK
	 *     Content-Type: application/json
	 *
	 *     [
	 *       {
	 *         "teacherID": 188,
	 *         "title": "Ms.",
	 *         "firstName": "Veronica",
	 *         "lastName": "Castro",
	 *         "timezone": "(GMT -6:00) America\/Mexico_City",
	 *         "teacherIntroduction": "Some introduction of the teacher.",
	 *         "countryResidence": "Mexico"
	 *       },
	 *       {
	 *         "teacherID": 191,
	 *         "title": "Mrs.",
	 *         "firstName": "Liudmila",
	 *         "lastName": "Yanukovich",
	 *         "timezone": "(GMT +2:00) Europe\/Kiev",
	 *         "teacherIntroduction": "Some introduction of the teacher.",
	 *         "countryResidence": "Ukraine"
	 *       }
	 *     ]
	 * @apiErrorExample {json}  Error-Response:
	 * HTTP/1.1 403 Forbidden
	 * Content-Type: application/json
     * {
	 *    "error": "This action not authorized"
	 * }
	 *
	 */
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 * @throws AuthorizationException
	 */
    public function index()
    {
    	// REVIEW: Maybe hide some teacher columns for this and some other methods
    	$user = Auth::user();

    	if( $user->group AND $user->group->active ){
		    return response(
		    	$user->group()->first()->teachers
				    ->each(function ($i, $k) {
					    $i->makeHidden(['profileImage', 'teacherIntroduction', 'gender']);
				    })
		    );
	    }

    	throw new AuthorizationException('This action not authorized');
    }

	/**
	 * @api {get} /api/teacher/:id View teacher by ID
	 * @apiName GetTeacher
	 * @apiGroup Teacher
	 * @apiDescription The endpoint returns an objects in json format.
	 * The object represents base information available on the teacher.
	 *
	 * @apiParam  (URI) {number} id teacherID - unique teacher ID
	 *
	 * @apiSuccess (teacher object) {number} teacherID Teacher's unique identifier.
	 * @apiSuccess (teacher object) {string} title Title of the teacher (Mr., Mrs., Ms., Dr., Prof.)
	 * @apiSuccess (teacher object) {string} firstName Teacher's first name
	 * @apiSuccess (teacher object) {string} lastName Teacher's last name
	 * @apiSuccess (teacher object) {string} timezone Teacher's time zone
	 * @apiSuccess (teacher object) {string} teacherIntroduction Introduction of the teacher
	 * @apiSuccess (teacher object) {string} countryResidence Teacher's country of residence
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 * Content-Type: application/json
	 *
	 * {
	 *    "teacherID": 188,
	 *    "title": "Ms.",
	 *    "firstName": "Veronica",
	 *    "lastName": "Castro",
	 *    "timezone": "(GMT -6:00) America\/Mexico_City",
	 *    "teacherIntroduction": "Some introduction of the teacher.",
	 *    "countryResidence": "Mexico"
	 * }
	 * @apiErrorExample {json}  Error-Response:
	 * HTTP/1.1 403 Forbidden
	 * Content-Type: application/json
	 * {
	 *    "error": "This action not authorized"
	 * }
	 */
	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Teacher  $teacher
	 * @return \Illuminate\Http\Response
	 */
	public function show( TeacherViewRequest $request )
	{
		return response( $request->teacher->makeHidden(['profileImage', 'teacherIntroduction', 'gender']) );
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    return $this->notImplemented();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    return $this->notImplemented();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
	    return $this->notImplemented();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
	    return $this->notImplemented();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
	    return $this->notImplemented();
    }


	/**
	 * @api {get} /api/teacher/email/:email View teacher by email
	 * @apiName GetTeacherByEmail
	 * @apiGroup Teacher
	 * @apiDescription The endpoint returns an objects in json format.
	 * The object represents base information available on the teacher.
	 *
	 * @apiParam (URI) {string} email Unique teacher email
	 *
	 * @apiSuccess (teacher object) {number} teacherID Teacher's unique identifier.
	 * @apiSuccess (teacher object) {string} title Title of the teacher (Mr., Mrs., Ms., Dr., Prof.)
	 * @apiSuccess (teacher object) {string} firstName Teacher's first name
	 * @apiSuccess (teacher object) {string} lastName Teacher's last name
	 * @apiSuccess (teacher object) {string} timezone Teacher's time zone
	 * @apiSuccess (teacher object) {string} teacherIntroduction Introduction of the teacher
	 * @apiSuccess (teacher object) {string} countryResidence Teacher's country of residence
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 * Content-Type: application/json
	 *
	 * {
	 *    "teacherID": 188,
	 *    "title": "Ms.",
	 *    "firstName": "Veronica",
	 *    "lastName": "Castro",
	 *    "timezone": "(GMT -6:00) America\/Mexico_City",
	 *    "teacherIntroduction": "Some introduction of the teacher.",
	 *    "countryResidence": "Mexico"
	 * }
	 * @apiErrorExample {json}  Error-Response:
	 * HTTP/1.1 403 Forbidden
	 * Content-Type: application/json
	 * {
	 *    "error": "This action not authorized"
	 * }
	 */
	/**
	 * Display the specified resource.
	 *
	 * @param  string  $email
	 * @return \Illuminate\Http\Response
	 */
	public function byEmail( TeacherViewRequest $request )
	{
		return response( $request->teacher->makeHidden(['profileImage', 'teacherIntroduction', 'gender']) );
	}

	/**
	 * @api {put | patch | delete} /api/teacher/:id/group/:group Assign to group/Unassign
	 * @apiName TeacherGroup
	 * @apiGroup Teacher
	 * @apiPermission Super Admin
	 * @apiDescription Assign teacher to a group or remove teacher from the group.
	 *                 <span class="type type__delete">DELETE</span> method removes the teacher from the group. Other permitted methods attach teacher to the group.
	 *
	 * @apiParam (URI) {number} id The teacher id (teacherID)
	 * @apiParam (URI) {number} group ID of the group
	 * @apiParam {string{..512}} [note]  Optional Admin's comment to the action.
	 * @apiParamExample {json} Request-Example:
	 * PUT /api/teacher/6/group/1
	 * Accept: application/json
	 * Content-Type: application/json
	 * Authorization: Bearer SOME_TOKEN_STRING_HERE
	 *
	 * {
	 *    "note": "Trial period for teacher"
	 * }
	 *
	 * @apiSuccess (result) {number} body 1 on success, 0 on failure.
	 *
	 * @apiSuccessExample {html} Success-Response:
	 * HTTP/1.1 202 Accepted
	 * Content-Type: text/html; charset=UTF-8
	 *
	 * 1
	 *
	 * @apiError (422) {json} UnprocessableEntity Data validation error.
	 * @apiErrorExample {json}  Error-Response:
	 * HTTP/1.1 422 Unprocessable Entity
	 * Content-Type: application/json
	 * {
	 *    "note": "The note may not be greater than 512 characters."
	 * }
	 */
	public function group( TeacherGroupRequest $request )
	{
		$g = Group::find( $request->group );

		$action = ('DELETE' == strtoupper( $request->method() ) ) ? 'detach' : 'attach';

		return response( (int)(bool) $g->member( Auth::user()->id, $action, 'teacher', $request->id, (string) $request->note ), 202 ); //$g->member( Auth::user()->id, $action, 'teacher', $request->id, (string) $request->note )
	}

	public function usuario( $id )
	{
		$teacher = Teacher::findOrFail( $id );

		if( !Auth::user()->can( 'create', $teacher ) )
			throw new \Illuminate\Auth\Access\AuthorizationException;

		try {
			User::create([
				'email' => $teacher->email,
				'password' => \Hash::make( $teacher->password )
			])->teacher()->save( $teacher );

			if( !$teacher->gender )
				dispatch( new GetGender( $teacher ) );
		}
		catch( \Illuminate\Database\QueryException $e ) {
			throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException( $e->getMessage(), $e );
		}

		return response( $teacher->usuario_id, 201 );
	}
}
