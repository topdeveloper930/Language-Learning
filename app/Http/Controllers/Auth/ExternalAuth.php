<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\SnAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ExternalAuth extends Controller {

	public function redirectToProvider( Request $request, $provider, $role )
	{
		$request->session()->put('userType', $role);

		if( $this->isDisableProvider( $provider, $role ) )
			return redirect()->back();

		return Socialite::driver( $provider )
						->with(['redirect' => route('sn_callback', ['provider' => $provider])])
		                ->redirect();
	}

	/**
	 *
	 * @param Request $request
	 * @param string  $provider
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function handleProviderCallback( Request $request, $provider )
	{
		$role = $request->session()->pull('userType' );

		$this->area = $role;
		$request->merge(['role' => $role]);

		try {
			$user = Socialite::driver( $provider )->user();
		}
		catch ( \Exception $e ) {
			return redirect()->back()
			                 ->withErrors([ $provider => ( $m = $e->getMessage() ) ? $m : trans('auth.provider_reject', [ 'provider' => trans("auth.$provider" )])]);
		}

		$errors = [];

		if( $ll_user = Auth::guard( $role )->user() ) {
			$ll_user->getInstance()->snAccounts()->create([
				'provider'     => $provider,
				'provider_uid' => $user->getId()
			]);
		}
		elseif ($auth_inst = $this->getAuthenticatableFromSnAccount( $role, $provider, $user->getId() )
		        OR $auth_inst = $this->search4AuthenticatableByEmail( $role, $provider, $user->getEmail(), $user->getId() )) {
			Auth::guard( $role )->login( $auth_inst );
		}
		else {
			$errors[ $provider ] = trans('auth.sn_user_not_found', [
					'role' => ucfirst( $role ),
					'email' => $user->getEmail(),
					'provider' => trans("auth.$provider")
				]
			);
		}

		return empty( $errors )
			? redirect()->back()
			: redirect()->back()->withErrors($errors);
	}

	private function isDisableProvider( $provider, $role )
	{
		$method = $provider . 'Account';

		if( $user = Auth::guard( $role )->user() AND $sn = $user->getInstance()->{$method}() )
			return $sn->delete();

		return false;
	}

	private function getAuthenticatableFromSnAccount( $role, $provider, $uid )
	{
		$sn = SnAccount::where([
			['provider', $provider],
			['provider_uid', $uid],
			['user_type', $this->role2Model( $role )]]
		)->first();

		return $sn ? $sn->authInst() : $sn;
	}

	private function search4AuthenticatableByEmail( $role, $provider, $email, $uid )
	{
		$model = $this->role2Model( $role );

		$sn_user = $model::where( 'email', $email )->first();

		$auth_inst = (!$sn_user OR $sn_user instanceof \Illuminate\Contracts\Auth\Authenticatable)
			? $sn_user
			: $sn_user->usuario;

		// Create SnAccount record
		if( $auth_inst ) {
			$sn_user->snAccounts()->create([
				'provider'     => $provider,
				'provider_uid' => $uid
			]);
		}

		return $auth_inst;
	}

	private function role2Model( $role )
	{
		switch ( $role ) {
			case 'student':
				return 'App\Student';
			case 'teacher':
				return 'App\Teacher';
			case 'admin':
			case 'member':
				return 'App\Member';
			default:
				return '';
		}
	}
}