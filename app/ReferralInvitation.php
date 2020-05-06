<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferralInvitation extends Model
{
	const CREATED_AT = 'created_on';
	const UPDATED_AT = null;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'referrer_id', 'name', 'email', 'note'
	];

	public function student()
	{
		return $this->belongsTo( Student::class, 'referrer_id', 'studentID' );
	}

	public function referral()
	{
		return $this->hasOne( Student::class, 'email', 'email' );
	}
}
