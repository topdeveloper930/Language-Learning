<?php


namespace App\Custom;


use App\Traits\ArrayObjectAccess;

class Style {
	use ArrayObjectAccess;

	public function __construct( $style )
	{
		if( is_array( $style ) ){
			$this->container = $style;

			if( $this->v )
				$this->offsetSet( 'href', asset( $this->href ) . '?v=' . $this->v );
			else
				$this->offsetSet( 'href', asset( $this->href ) );
		}
		else
			$this->offsetSet( 'href', asset( $style ) );
	}

	public function __toString()
	{
		return '<link rel="stylesheet"  href="' . $this->href . '">';
	}
}