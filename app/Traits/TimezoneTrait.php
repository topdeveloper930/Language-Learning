<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 30.06.2018
 * Time: 15:53
 */

namespace App\Traits;


trait TimezoneTrait {

	public function timezone_numeric()
	{
		return substr( $this['timezone'], 0, strpos( $this['timezone'], ')' ) + 1 );
	}

	public function timezone_code()
	{
		return trim(substr( $this['timezone'], strpos( $this['timezone'], ')' ) + 1 ) );
	}

	/**
	 * @param string $fallback optional fallback parameter
	 *
	 * @return mixed
	 */
	public function timezone_offset( $fallback = 'GMT' )
	{
		$start = strpos( $this['timezone'], '(GMT' );

		if( false === $start )
			return $fallback;
		else
			$start++; // Get rid of opening parenthesis

		$length = strpos( $this['timezone'], ')', $start ) - $start;

		if( $length <= 0 )
			return $fallback;

		$offset = trim( substr( $this['timezone'], $start, $length ) );

		$offset = explode(' ', $offset );

		switch ( count( $offset ) )
		{
			case 2:
				if( strlen( $offset[1] ) < 6 ) {
					$sign = $offset[1][0];
					$offset[1] = $sign . str_replace( $sign, '0', $offset[1] );
				}

				return implode( '', $offset );
			default:
				return 'GMT';
		}
	}

	public function timezone_offset_hours()
	{
		$tz_offset = $this->timezone_offset();

		return ( 'GMT' == $tz_offset )
			? '+00:00'
			: str_replace('GMT', '', $tz_offset);
	}

	public function timezone_id_or_offset()
	{
		$tz_code = $this->timezone_code();

		if( strpos( $tz_code, '/' ) > 0 )
			return $tz_code;

		return $this->timezone_offset();
	}

	public function formatUTCtoMyTimeZone( $datetime, $format = 'Y-m-d H:i:s' )
	{
		$dt = new \DateTime( $datetime, new \DateTimeZone( 'UTC' ) );
		$dt->setTimezone( new \DateTimeZone( $this->timezone_code() ) );

		return $dt->format( $format );
	}
}