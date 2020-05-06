<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected function notImplemented()
	{
		return response( 'Method not implemented', 400 );
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

		return response( 'Internal Server Error', 500 );
	}
}
