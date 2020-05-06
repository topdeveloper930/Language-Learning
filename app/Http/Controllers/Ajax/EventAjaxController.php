<?php


namespace App\Http\Controllers\Ajax;


use App\Event;
use App\Http\Controllers\AjaxController;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\EventRequest;

class EventAjaxController extends AjaxController {

	public function store( CreateEventRequest $request )
	{
		return response()->json( Event::create( $request->all() ) );
	}

	/**
	 * @param EventRequest $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update( EventRequest $request )
	{
		$request->inst->updated_by = $request->guardName() . ':' . $request->currentUserID();

		return response()->json(
			$request->inst->update( $request->only(['eventStart', 'eventEnd']) ),
			204
		);
	}

	/**
	 * Set class cancelled.
	 *
	 * @param  EventRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy( EventRequest $request )
	{
		$request->inst->active = 0;
		$request->inst->updated_by = $request->guardName() . ':' . $request->currentUserID();

		return response()->json( $request->inst->save(), 202 );
	}
}