<?php

namespace App\Http\Controllers\Ajax;

use App\CalendarExternal;
use App\Events\CalendarIntegrationEvent;
use App\Http\Controllers\AjaxController;
use App\Http\Requests\CreateCalendarExternalRequest;
use App\Services\Auth\UserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CalendarExternalAjaxController extends AjaxController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( CreateCalendarExternalRequest $request )
    {
    	$cal = ( new CalendarExternal )->createWithAuthToken( $request->all() );

    	// Trigger syncing events
	    event( new CalendarIntegrationEvent( $cal ) );

	    return response()->json( $cal->id );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function show( $id = null )
    {
        return response()->json( $this->getCalendar( $id ) );
    }

	public function update( Request $request )
	{
		$gCalendar = $this->getCalendar( $request->query( 'id' ) );

		if( !$request->input( 'authentication_code' ) )
			return response()->json( [ 'error' => __( 'validation.required', [ 'attribute' => 'authentication_code' ] ) ], 422 );

		return response( (int) $gCalendar->updateToken( $request->input( 'authentication_code' ) ), 202 );
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function destroy( $id )
    {
	    return response()->json( (int) $this->getCalendar( $id )->delete(), 204 );
    }

	/**
	 * @param int|null $id
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
	 * @throws \Illuminate\Auth\AuthenticationException
	 */
    protected function getCalendar( $id = null )
    {
    	$id = (int) $id;
	    $user = Auth::user();

	    if( $id ) {
		    $gCalendar = CalendarExternal::findOrFail( $id );

		    if( $gCalendar AND !$user->can( 'view', $gCalendar ) )
			    throw new \Illuminate\Auth\AuthenticationException( 'Forbidden' );
	    }
	    else {
		    $gCalendar = $user->getInstance()->gcalendar;
	    }

    	if( !$gCalendar )
    		throw new \Illuminate\Database\Eloquent\ModelNotFoundException;

    	return $gCalendar;
    }
}
