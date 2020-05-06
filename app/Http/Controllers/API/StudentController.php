<?php

namespace App\Http\Controllers\API;

use App\GroupHistory;
use App\Http\Controllers\APIController;
use App\Http\Requests\StudentCreateRequest;
use App\Http\Requests\StudentRequest;
use App\Student;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class StudentController
 * @package App\Http\Controllers\API
 */
class StudentController extends APIController
{

	/**
	 * @api {get} /api/student/:status View all
	 * @apiName GetStudents
	 * @apiGroup Student
	 * @apiDescription The endpoint returns array of objects in json format.
	 * Every object represents base information on the student available.
	 *
	 * @apiParam (URI) {string="active","inactive"} [status] Optional URI parameter to filter students by the status.
	 *
	 * @apiSuccess (200){json} response array of objects.
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 * Content-Type: application/json
	 *
	 * [
	 *    {
	 *       "studentID": 21617,
	 *       "title": "Ms.",
	 *       "firstName": "Jane",
	 *       "lastName": "Doe",
	 *       "email": "sample@email.us",
	 *       "active": "Active"
	 *    },
	 *    {
	 *       "studentID": 21618,
	 *       "title": "Mr.",
	 *       "firstName": "John",
	 *       "lastName": "Doe",
	 *       "email": "sample2@email.us",
	 *       "active": "Inactive"
	 *     }
	 *  ]
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
     * @throws
     */
    public function index( $active = null )
    {
	    $user = Auth::user();

	    if( $user->can('create', Student::class) AND $user->group AND $user->group->exists() ) {
		    try{
			    return response($user->group->getAllStudents( $active ));
		    }
		    catch ( \Exception $e ) {
			    return $this->serverError( $e );
		    }
	    }

	    throw new AuthorizationException('Not authorized');
    }

	public function filter(Request $request)
	{
		return $this->index( $request->active );
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
	 * @api {post} /api/student Create
	 * @apiName StudentCreate
	 * @apiGroup Student
	 * @apiDescription Create a student record in Live Lingua.
	 *
	 * @apiParam {string{..150}} firstName First name. May contain multiple names.
	 * @apiParam {string{..150}} lastName Last name
	 * @apiParam {string{..10}} title Title, honorific (Mr., Mrs., Ms., Dr., Prof.)
	 * @apiParam {string{..150}} email Email. The parameter is required and must be unique. However, you can auto generate it using your own domain, for instance.
	 *
	 * @apiParamExample {json} Request-Example:
	 * POST {{host}}/api/student
	 * Accept: application/json
	 * Content-Type: application/json
	 * Authorization: Bearer SOME_TOKEN_STRING_HERE
	 * {
	 *    "firstName": "Janet",
	 *    "lastName": "Doe",
	 *    "title": "Mrs.",
	 *    "email": "janet_doe@email.cn"
	 * }
	 *
	 * @apiSuccess (Student object in responce) {number} studentID Student's unique identifier
	 * @apiSuccess (Student object in responce) {string} title Title of the student
	 * @apiSuccess (Student object in responce) {string} firstName Student's first name
	 * @apiSuccess (Student object in responce) {string} lastName Student's last name
	 * @apiSuccess (Student object in responce) {string} email Email of the student
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 201 Created
	 * Content-Type: application/json
	 *
	 * {
	 *    "title": "Mrs.",
	 *    "firstName": "Janet",
	 *    "lastName": "Doe",
	 *    "email": "janet_doe@email.cn",
	 *    "studentID": 21630
	 * }
	 *
	 * @apiUse ValidationError
	 */
    /**
     * Store a newly created resource in storage.
     *
     * @param  StudentCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentCreateRequest $request)
    {
    	try {
    		// We don't want data other than required. Everything else to be auto generated.
		    $data = ( Auth::user()->isSuperAdmin() )
			    ? $request->all()
			    : $request->only([ 'title', 'firstName', 'lastName', 'email' ]);

		    $student = app( Student::class )->createWithAutoFill( $data );

		    // Assign the student to the user group (group_id 0 means no-group regular LL student)
		    if( $request->group_id )
		        $student->addToGroup( Auth::user()->id,  $request->group_id );

		    return response( $student, 201 );
	    }
    	catch ( \Exception $e ) {
    		return $this->serverError( $e );
	    }
    }

	/**
	 * @api {get} /api/student/:student View Student by ID
	 * @apiName GetStudent
	 * @apiGroup Student
	 * @apiDescription The endpoint returns an objects in json format.
	 * The object represents base information available on the student.
	 *
	 * @apiParam (URI) {number} student URI parameter student ID
	 *
	 * @apiSuccess (Student object in responce) {number} student Student's unique identifier (studentID).
	 * @apiSuccess (Student object in responce) {string} title Title of the student (Mr., Mrs., Ms., Dr., Prof.)
	 * @apiSuccess (Student object in responce) {string} firstName Student's first name
	 * @apiSuccess (Student object in responce) {string} lastName Student's last name
	 * @apiSuccess (Student object in responce) {string} email Student's email
	 * @apiSuccess (Student object in responce) {string} active Status of the student (either Active or Inactive)
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 200 OK
	 * Content-Type: application/json
	 *
	 * {
	 *    "studentID": 21630
	 *    "title": "Mrs.",
	 *    "firstName": "Janet",
	 *    "lastName": "Doe",
	 *    "email": "janet_doe@email.cn",
	 *    "active": "Active"
	 * }
	 *
	 * @apiError (403) {json} Forbidden The User with <code>Bearer Token</code> provided is not allowed for requested action. It can also mean that the requested student does not exist or does not belong to the User's group.
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
     * @param  Student $student
     * @return \Illuminate\Http\Response
     */
    public function show( StudentRequest $request )
    {
        return response( $request->inst );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	    return $this->notImplemented();
    }

	/**
	 * @api {put} /api/student/:student Update
	 * @apiName UpdateStudent
	 * @apiGroup Student
	 * @apiDescription Update student data. <code>PATCH</code> method can be used as well.
	 *
	 * @apiParam (URI) {number} student Student's ID
	 * @apiParam {string{..150}} [firstName] First name
	 * @apiParam {string{..150}} [lastName] Last name
	 * @apiParam {string{..10}} [title] Title, honorific (Mr., Mrs., Ms., Dr., Prof.)
	 * @apiParam {string{..150}} [email] Email. The parameter is required and must be unique. However, you can auto generate it using your own domain, for instance.
	 *
	 * @apiSuccess (Student object in responce) {number} studentID Student's unique identifier.
	 * @apiSuccess (Student object in responce) {string} title Title of the student (Mr., Mrs., Ms., Dr., Prof.)
	 * @apiSuccess (Student object in responce) {string} firstName Student's first name
	 * @apiSuccess (Student object in responce) {string} lastName Student's last name
	 * @apiSuccess (Student object in responce) {string} email Student's email
	 * @apiSuccess (Student object in responce) {string} active Status of the student (either Active or Inactive)
	 *
	 * @apiSuccessExample {json} Success-Response:
	 * HTTP/1.1 202 Accepted
	 * Content-Type: application/json
	 *
	 * {
	 *    "studentID": 21630
	 *    "title": "Mrs.",
	 *    "firstName": "Janet",
	 *    "lastName": "Doe",
	 *    "email": "janet_doe@email.cn",
	 *    "active": "Active"
	 * }
	 *
	 * @apiUse ValidationError
	 */
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StudentRequest $request)
    {
	    $request->inst->fill( $request->all() );

	    try {
		    return ( $request->inst->save() )
			    ? response( $request->inst, 202 )
			    : response( 'Cannot process your request. Please, try later', 503 );
	    }
	    catch ( \Exception $e ) {
		    return $this->serverError( $e );
	    }
    }

	/**
	 * @api {delete} /api/student/:student Remove
	 * @apiName RemoveStudent
	 * @apiGroup Student
	 * @apiDescription Removes student from the group.
	 *
	 * @apiParam (URI) {number} student Student's ID
	 * @apiParam {string{..512}} [note] Comment or remark (urlencoded).
	 *
	 * @apiSuccess {string} body OK.
	 * @apiSuccessExample {text/html} Success-Response:
	 * HTTP/1.1 202 Accepted
	 * Content-Type: text/html; charset=UTF-8
	 *
	 * OK
	 *
	 * @apiError (422) {json} UnprocessableEntity Data validation error.
	 * @apiErrorExample {json} Error-Response:
	 * HTTP/1.1 422 Unprocessable Entity
	 * Content-Type: application/json
	 * {
	 *    "note": ["The note may not be greater than 512 characters."]
	 * }
	 *
	 * @apiUse ServerError
	 */
    /**
     * Remove the specified resource from group:
     *  set student Inactive, remove from group, write log to group_history.
     *
     * @param  StudentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentRequest $request)
    {
	    $user = Auth::user();

    	try {
		    $request->inst->active = 'Inactive';
		    $request->inst->save();

			// Write log to group_history
		    foreach ( $request->inst->group()->get() as $group )
			    GroupHistory::create([
			        'group_id'      => $group->groupID,
				    'usuario_id'    => $user->id,
				    'student_id'    => $request->inst->studentID,
				    'action'        => 'detach',
				    'note'          => (string) $request->note
			    ]);

		    return ( $request->inst->group()->detach() )
			    ? response( 'OK', 202 )
			    : response( 'Cannot process your request. Please, try later', 503 );
	    }
	    catch ( \Exception $e ) {
			return $this->serverError( $e );
	    }
    }

	/**
	 * @api {patch} /api/student/:student/:status Activate/Deactivate
	 * @apiName ActivateStudent
	 * @apiGroup Student
	 * @apiDescription Sets Student Active or Inactive.
	 *
	 * @apiParam (URI) {number} student Student's ID
	 * @apiParam (URI) {string{"Active","Inactive"}} status Status to apply to the Student.
	 *
	 * @apiSuccess {string} body OK.
	 * @apiSuccessExample {text/html} Success-Response:
	 * HTTP/1.1 202 Accepted
	 * Content-Type: text/html; charset=UTF-8
	 *
	 * OK
	 *
	 * @apiUse ServerError
	 */
    public function status(StudentRequest $request)
    {
	    $request->inst->active = ucfirst( $request->status );
	    try {
		    return ( $request->inst->save() )
			    ? response( 'OK', 202 )
			    : response( 'Cannot process your request. Please, try later', 503 );
	    }
	    catch ( \Exception $e ) {
		    return $this->serverError( $e );
	    }
    }
}
