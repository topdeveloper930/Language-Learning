<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationRegions extends Model
{
    protected $table = 'locationRegions';

    public $timestamps = false;

    public function country()
    {
    	return $this->belongsTo( LocationCountries::class, 'id_country' );
    }
}
