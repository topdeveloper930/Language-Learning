<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\AjaxController;
use App\Http\Requests\StudentPhotoRequest;
use App\Http\Requests\StudentUpdateRequest;

/**
 * Class StudentsAjaxController
 * @package App\Http\Controllers\Ajax
 */
class StudentsAjaxController extends AjaxController
{
    public function show( $id )
    {
    	// TODO

    	return response()->json('');
    }

	/**
	 * @param StudentUpdateRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update( StudentUpdateRequest $request )
	{
		return response()->json(
			[ 'success' => $request->runUpdates() ]
		);
	}

	/**
	 * @param StudentPhotoRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function photo( StudentPhotoRequest $request )
	{
		return response()->json(
			[ 'success' => $request->updatePhoto() ]
		);
	}
}
