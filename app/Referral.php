<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    public $timestamps = false;

    protected $hidden = null;

	protected $guarded = [];

	public function student()
	{
		return $this->belongsTo( Student::class, 'id', 'studentID' );
	}

	public function coupon()
	{
		return $this->belongsTo( Coupon::class );
	}
}
