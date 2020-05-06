<?php


namespace App\Http\Controllers;


use App\Services\Auth\UserType;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller {

	/**
	 * @var Authenticatable
	 */
	protected $user;

	/**
	 * @var bool
	 */
	protected $ajaxOnly = false;

	/**
	 * @var string
	 */
	protected $user_type;

	public function callAction($method, $parameters)
	{
		$this->preflight();
		return parent::callAction( $method, $parameters );
	}

	protected function preflight()
	{
		if( $this->ajaxOnly AND !request()->ajax() )
			throw new \Illuminate\Auth\Access\AuthorizationException( "Not acceptable method" );


		$this->user = Auth::user();
		$this->user_type = ( $this->user instanceof UserType )
			? $this->user->getType()
			: 'guest';
	}
}