<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\GuestController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends GuestController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
	    showLoginForm as show;
    }

	/**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $area;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( Request $request )
    {
    	$this->setArea( $request );

	    parent::__construct();

        $this->middleware('guest')->except('logout');
	    $this->middleware('guest:student')->except('logout');
    }

	/**
	 * Show the application's login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showLoginForm()
	{
		$this->prepareTemplate();
		$this->make();

		$this->user OR $this->user = request()->user('student') OR $this->user = request()->user('teacher');

		if( $this->user ) {
			request()->merge(['role' => $this->user->getArea()]);
			return redirect($this->redirectTo());
		}

		$this->tplConfig->area       = $this->area;
		$this->tplConfig->page_title = __('auth.login_title');

		return $this->show();
	}

	/**
	 * Log the user out of the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function logout(Request $request)
	{
		$this->guard()->logout();

		$request->session()->invalidate();

		$role = str_singular( $this->area );

		$this->notification( __( 'auth.logged_out', [ 'role' => __( "pages.$role.$role" ) ] ) );

		return redirect( url( '/auth/login' ) );
	}

	/**
	 * Get the guard to be used during authentication.
	 * Students, Teachers and Admin can use legacy authentication.
	 *
	 * @return \Illuminate\Contracts\Auth\StatefulGuard
	 */
	protected function guard()
	{
		return ( 'students' == $this->area OR 'teachers' == $this->area OR 'admin' == $this->area )
			? Auth::guard( str_singular( $this->area ) )
			: Auth::guard();
	}

	protected function redirectTo()
	{
		if( $role = request('role') )
			return route( $role, ['controller' => 'dashboard'] );

		return $this->redirectTo;
	}

	private function setArea( $request )
	{
		if( !$request->route() ) {
			$this->area = 'guest';
			return;
		}

		$route_name = $request->route()->getName();
		$this->area = ( 'login' ==  $route_name OR 'logout' == $route_name )
			? $request->route( 'role' )
			: $request->get( 'role', $request->route()->getPrefix() );
	}
}
