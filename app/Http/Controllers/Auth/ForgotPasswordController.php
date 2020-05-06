<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\GuestController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends GuestController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

	use SendsPasswordResetEmails {
		showLinkRequestForm as show;
	}

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = '/admin/admin-dashboard.php';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        parent::__construct();

        if( 'admin' != request( 'area' ) )
        	$this->redirectTo = '/' . request( 'area' ) . '/dashboard';
    }

	/**
	 * Display the form to request a password reset link.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showLinkRequestForm()
	{
		$this->prepareTemplate();
		$this->make();
		$this->tplConfig->area       = request( 'area' );
		$this->tplConfig->page_title = __('auth.reset_title');

		return $this->show();
	}

	/**
	 * Send a reset link to the given user.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function sendResetLinkEmail(Request $request)
	{
		$this->validateEmail($request);

		// We will send the password reset link to this user. Once we have attempted
		// to send the link, we will examine the response then see the message we
		// need to show to the user. Finally, we'll send out a proper response.
		$response = $this->broker()->sendResetLink(
			$request->only('email' )
		);

		return $response == Password::RESET_LINK_SENT
			? $this->sendResetLinkResponse($response)
			: $this->sendResetLinkFailedResponse($request, $response);
	}

	/**
	 * Get the broker to be used during password reset.
	 *
	 * @return \Illuminate\Contracts\Auth\PasswordBroker
	 */
	public function broker()
	{
		$pass = ( 'students' == request( 'area' ) ) ? 'students' : 'users';
		return Password::broker( $pass );
	}
}
