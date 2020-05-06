<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
	    if ( $request->expectsJson() ) {
	    	if ( $exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException OR $exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ) {
			    return response()->json( [
				    'error' => 'Resource not found'
			    ], 404 );
		    }
		    elseif ( $exception instanceof \Illuminate\Auth\Access\AuthorizationException ) {
			    return response()->json( [
				    'error' => $exception->getMessage()
			    ], 403 );
		    }
	    	elseif ( $exception instanceof HttpException) {
			    return response()->json( [
				    'error' => ($exception->getMessage()) ? $exception->getMessage() : 'Method not allowed'
			    ], ($exception->getStatusCode()) ? $exception->getStatusCode() : 400 );
		    }
	    }

	    return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
        	$error = $exception->getMessage() OR $error = 'Unauthenticated.';
            return response()->json([ 'error' => $error ], 401);
        }

	    if ( $request->is('students/*') OR $request->is('teachers/*') OR $request->is('admin/*') OR $request->is('group/*') ) {
		    return redirect()->guest(route('login', ['role' => $request->route()->getPrefix()]));
	    }

	    // Default role is student
        return redirect()->guest( route('login', ['role' => 'students'] ) );
    }
}
