<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Log;
use Sarfraznawaz2005\VisitLog\Facades\VisitLog;

class APIController extends Controller
{
	public function callAction($method, $parameters)
	{
		if( env( 'LOG_VISITS', 0 ) )
			VisitLog::save();

		return parent::callAction($method, $parameters);
	}

	protected function notImplemented()
	{
		return response()->json( 'Method not implemented', 400 );
	}

	/**
	 * @param \Exception $exception
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function serverError( $exception )
	{
		if( !$exception instanceof \Exception )
			$exception = ( is_string($exception) ) ? new \Exception( $exception ) : new \Exception( 'Unknown error' );

		Log::error( $exception->getMessage(), $exception->getTrace() );
		return response()->json( 'Internal Server Error', 500 );
	}
}
