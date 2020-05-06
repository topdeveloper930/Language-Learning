<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\GuestController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends GuestController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords{
	    showResetForm as show;
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo;

    protected $area;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( Request $request )
    {
    	$this->area = $request->get( 'area', 'students' );
    	$this->redirectTo = route( 'login', [ 'role' => $this->area ] );
        $this->middleware('guest');
	    parent::__construct();
    }

	/**
	 * Display the password reset view for the given token.
	 *
	 * If no token is present, display the link request form.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  string|null  $token
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function showResetForm(Request $request, $token = null)
	{
		$this->prepareTemplate();
		$this->make();
		$this->tplConfig->area       = request()->route('role');
		$this->tplConfig->role       = str_singular( $this->tplConfig->area );
		$this->tplConfig->page_title = __('auth.reset_title');

		return $this->show($request, $token );
	}

	/**
	 * Get the guard to be used during password reset.
	 *
	 * @return \Illuminate\Contracts\Auth\StatefulGuard
	 */
	protected function guard()
	{
		return Auth::guard( str_singular( $this->area ) );
	}


	/**
	 * Get the broker to be used during password reset.
	 *
	 * @return \Illuminate\Contracts\Auth\PasswordBroker
	 */
	public function broker()
	{
		$pass = ( 'students' == $this->area ) ? 'students' : 'users';
		return Password::broker( $pass );
	}



	/**
	 * Reset the given user's password.
	 *
	 * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
	 * @param  string  $password
	 * @return void
	 */
	protected function resetPassword($user, $password)
	{
		$fields = [
			'password' => bcrypt($password),
			'remember_token' => Str::random(60),
		];

		if( 'students' == $this->area )
			$fields[ 'password128' ] = '';

		$user->forceFill($fields)->save();

		$this->guard()->login($user);
	}

	/**
	 * Get the password reset validation rules.
	 *
	 * @return array
	 */
	protected function rules()
	{
		return [
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|confirmed|min:6',
		];
	}
}
