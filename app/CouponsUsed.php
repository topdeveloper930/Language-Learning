<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponsUsed extends Model
{
	protected $table = 'coupons_used';

	const CREATED_AT = 'date';
	const UPDATED_AT = null;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [ 'coupon_id', 'studentID', 'transactionID' ];

	public function coupon()
	{
		return $this->belongsTo( Coupon::class );
	}

	public function transaction()
	{
		return $this->belongsTo( Transaction::class, 'transactionID', 'transactionID' );
	}

	public function student()
	{
		return $this->belongsTo( Student::class, 'studentID', 'studentID' );
	}
}
