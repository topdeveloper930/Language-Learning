<?php


namespace App\Traits;


trait Parseable {

	public function parseStringToArray( $value, $paramName )
	{
		$output = [];

		parse_str( $value, $output );

		return (isset( $output[ $paramName ] ) ) ? $output[ $paramName ] : [];
	}

	public function urlEncodeArray( $values, $paramName )
	{
		$result = "";

		foreach( $values as $val )
			$result .= sprintf('%s=%s&', urlencode( $paramName . '[]' ), urlencode( stripslashes( $val ) ) );

		return rtrim( $result, '&' );
	}
}