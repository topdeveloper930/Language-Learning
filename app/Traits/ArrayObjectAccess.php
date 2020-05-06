<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 02.12.2018
 * Time: 21:34
 */

namespace App\Traits;


trait ArrayObjectAccess {

	/**
	 * @var array
	 */
	protected $container = [];


	public function offsetGet( $offset )
	{
		return ( isset( $this->container[ $offset] ) )
			? $this->container[ $offset ]
			: null;
	}

	public function offsetExists( $offset )
	{
		return isset( $this->container[ $offset ] );
	}

	public function offsetSet( $offset, $value )
	{
		if( $offset )
			$this->container[ $offset ] = $value;
	}

	public function offsetUnset( $offset )
	{
		unset( $this->container[ $offset ] );
	}

	public function __get( $name )
	{
		return $this->offsetGet( $name );
	}

	public function __set( $name, $value )
	{
		$this->offsetSet($name, $value);
	}
}