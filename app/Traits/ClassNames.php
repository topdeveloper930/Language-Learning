<?php


namespace App\Traits;


trait ClassNames {

	public function className()
	{
		return ( new \ReflectionClass( $this ) )->getName();
	}

	public function classShortName()
	{
		return ( new \ReflectionClass( $this ) )->getShortName();
	}

	public function classNamespace()
	{
		return ( new \ReflectionClass( $this ) )->getNamespaceName();
	}
}