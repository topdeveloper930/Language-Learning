<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReferralCredit extends Model
{
	const CREATED_AT = 'created_on';
	const UPDATED_AT = null;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'owner_id', 'amount', 'coupon_id', 'transactionID', 'note'
	];

	public function transaction()
	{
		return $this->belongsTo( Transaction::class, 'transactionID', 'transactionID' );
	}

	public function coupon()
	{
		return $this->belongsTo( Coupon::class );
	}

	public static function remaining( $owner_id )
	{
		return (float) DB::table( (new static)->getTable() )
			->select( DB::raw('SUM(amount) AS credits') )
			->where('owner_id', $owner_id)
			->first()->credits;
	}
}
