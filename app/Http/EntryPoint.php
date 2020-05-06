<?php

namespace App\Http;

use App\Traits\ClassNames;

abstract class EntryPoint
{
	use ClassNames;

	protected $controller_suffix;

	public function __construct()
	{
		abort_if( is_null( $this->controller_suffix ), 404 );
	}

	public function callClass( $controller, $id = null )
	{
		$class = $this->classNamespace() . '\\' . ucfirst( camel_case( $controller ) ) . $this->controller_suffix;

		abort_unless( class_exists( $class ) AND is_callable( $inst = new $class ), 404 );

		return call_user_func( $inst, $id );
	}

	public function callSearch( $controller, $search )
	{
		$params = explode('/', $search );

		$class = $this->classNamespace() . '\\' . ucfirst( camel_case( $controller ) ) . $this->controller_suffix;

		abort_unless( class_exists( $class ) AND is_callable( $inst = new $class ), 404 );

		return call_user_func_array( $inst, $params );
}

	public function callAjax( $controller, $id = null, $action = 'show' )
	{
		$class = '\\App\\Http\\Controllers\\Ajax\\' . ucfirst( camel_case( $controller ) ) . 'AjaxController';

		abort_unless( method_exists($class, $action) AND (new \ReflectionMethod($class, $action))->isPublic(), 404 );

		return ( new $class )->callAction( $action, [ 'id' => $id ] );
	}
}
