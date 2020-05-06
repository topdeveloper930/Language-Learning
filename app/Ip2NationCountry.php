<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ip2NationCountry extends Model
{
	protected $table = 'ip2nationCountries';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'code';

	public $timestamps = false;

	public function ips()
	{
		return $this->hasMany( Ip2Nation::class, 'country', 'code' );
	}
}
