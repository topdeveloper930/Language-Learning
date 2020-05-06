<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationCountries extends Model
{
    protected $table = 'locationCountries';

    public $timestamps = false;

	public function regions()
	{
		return $this->hasMany(LocationRegions::class, 'id_country' );
	}

	public function originTeachers()
	{
		return $this->hasMany( Teacher::class, 'countryOriginID');
	}

	public function resideTeachers()
	{
		return $this->hasMany( Teacher::class, 'countryResidenceID');
	}
}
