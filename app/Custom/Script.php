<?php


namespace App\Custom;


use App\Traits\ArrayObjectAccess;

class Script {
	use ArrayObjectAccess;

	public function __construct( $script, $head = false )
	{
		if( is_array( $script ) ){
			$this->container = $script;

			if( $this->v )
				$this->offsetSet( 'src', asset( $this->src ) . '?v=' . $this->v );
			else
				$this->offsetSet( 'src', asset( $this->src ) );
		}
		else
			$this->offsetSet( 'src', asset( $script ) );

		$this->offsetSet( 'head', $head );
	}

	public function __toString()
	{
		$extra = '';

		if ( $this->head AND $this->defer )
			$extra .= 'defer ';

		if ( $this->head AND $this->async )
			$extra .= 'async';

		!$extra OR $extra = ' ' . trim( $extra );

		return '<script' . $extra . ' src="' . $this->src . '"></script>';
	}
}