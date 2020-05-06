<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ip2Nation extends Model
{
	protected $table = 'ip2nation';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'ip';

	public $timestamps = false;

	public function state()
	{
		return $this->belongsTo( Ip2NationCountry::class, 'country', 'code' );
	}

	public static function countryByIP( $ip )
	{
		$c = \DB::table('ip2nation')
			->join('ip2nationCountries', 'ip2nationCountries.code', '=', 'ip2nation.country' )
			->whereRaw("ip2nation.ip < INET_ATON('$ip')")
			->orderBy('ip2nation.ip', 'desc')
			->first();

		return $c ? $c->country : null;
	}
}
