<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\AjaxController;
use App\Http\Requests\TeacherPhotoRequest;
use App\Http\Requests\TeacherUpdateRequest;
use App\Teacher;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handles teachers of the authenticated student.
 *
 * Class TeachersAjaxController
 * @package App\Http\Controllers\Ajax
 */
class TeachersAjaxController extends AjaxController
{
    public function show( $id )
    {
    	// FIXME: does not work for admin (teacher?)
    	$teachers = ( $id )
		    ? $this->user->teachers->find( $id )
		    : $this->user->teachers()->distinct()->get();

    	return response()->json( $teachers );
    }

	/**
	 * @param TeacherUpdateRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update( TeacherUpdateRequest $request )
	{
		return response()->json(
			[ 'success' => $request->runUpdates() ]
		);
	}

	/**
	 * @param TeacherPhotoRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function photo( TeacherPhotoRequest $request )
	{
		return response()->json(
			[ 'success' => $request->updatePhoto() ]
		);
	}

	/**
	 * The language that the teacher is assigned to the student with.
	 * Assume request done from the authenticated student's page.
	 */
    public function language( $id )
    {
    	$studentID = Auth::user()->studentID;

	    $validator = Validator::make([ 'id' => $id, 'studentID' => $studentID ], [
		    'id'        => 'required|numeric',
		    'studentID' => 'required|numeric'
	    ]);

	    if ( $validator->fails() )
		    throw new ValidationException( $validator );

	    $language = DB::table( 'teacher2student' )
	      ->select( 'language' )
	      ->where([
	      	'teacherID' => $validator->valid()['id'],
	        'studentID' => $validator->valid()['studentID']
	      ])->first();

	    if( !$language OR !property_exists( $language, 'language' ) )
		    throw new AuthenticationException( 'Forbidden' );

	    return response()->json( $language->language );
    }

	/**
	 * Teacher schedule.
	 * @param int $id Teacher ID
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws AuthenticationException
	 * @throws ValidationException
	 */
	public function schedule( $id )
	{
		$validator = Validator::make(array_merge( request()->all(), [ 'id' => $id ] ), [
			'id'    => 'required|numeric',
			'start' => 'required|date',
			'end'   => 'required|date|after:start',
		]);

		if ( $validator->fails() )
			throw new ValidationException( $validator );

		if( !$teacher = Teacher::find( (int) $id ) )
			throw new NotFoundHttpException();

		if( !$this->user->can( 'view', $teacher ) )
			throw new AuthenticationException( 'Forbidden' );

		// "start" and "end" are sent in UTC.
		$start_teacherTZ = $teacher->formatUTCtoMyTimeZone( $validator->valid()['start'] );
		$end_teacherTZ = $teacher->formatUTCtoMyTimeZone( $validator->valid()['end'] );

		$retval = [
			'unavailability'    => $teacher->unavailabilities,
			'timeoff'           => $teacher->timeOff()
			                               ->where( 'active', 1 )
			                               ->where( 'startDate', '<=', $end_teacherTZ )
			                               ->where( 'endDate', '>=', $start_teacherTZ )
			                               ->select('teacherID', 'startDate', 'endDate')
			                               ->get()->toArray(),
			'trials'            => $teacher->trialClasses( $start_teacherTZ, $end_teacherTZ ),
			'lessons'           => $teacher->classes()
			                               ->where( 'active', 1 )
			                               ->where( 'eventStart', '>=', $validator->valid()['start'] )
			                               ->where( 'eventStart', '<=', $validator->valid()['end'] )
			                               ->select( 'teacherID', 'eventStart', 'eventEnd' )
			                               ->get()->toArray()

		];

		return response()->json( $retval );
	}

	/**
	 * Dummy method and data for test ajax call from teacher availability stats script.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function availability_stats()
	{
		return response()->json([ 'data' => [
			['id' => 1, 'name' => 'Some One', 'email' => 'e@mail.com', 'availability' => 3],
			['id' => 2, 'name' => 'Some Two', 'email' => 'f@mail.com', 'availability' => 2.5],
			['id' => 3, 'name' => 'Some Three', 'email' => 'g@mail.com', 'availability' => 4.1],
		], 'payload' => ['length' => 3, 'search' => null, 'column' => 0, 'dir' => 'asc', 'isAdmin' => null, 'draw' => 1] ]);
	}
}
