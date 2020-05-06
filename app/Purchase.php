<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'transactionID', 'gateway_reference', 'courseType', 'hours', 'numStudents', 'coupon_code', 'giftcard_code',
		'description', 'balance', 'result', 'error'
	];

	protected $casts = [ 'balance' => 'array', 'result' => 'array' ];

	public function transaction()
	{
		return $this->belongsTo( Transaction::class, 'transactionID', 'transactionID' );
	}

	public function course()
	{
		return $this->belongsTo( CourseList::class, 'courseType', 'courseType' );
	}

	public function coupon()
	{
		return $this->belongsTo( Coupon::class, 'coupon_code', 'code' );
	}

	public function giftcard()
	{
		return $this->belongsTo( GiftCard::class, 'giftcard_code', 'code' );
	}
}
