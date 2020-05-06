<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AffiliateLog extends Model
{
	const CREATED_AT = 'date';
	const UPDATED_AT = null;

	protected $table = 'affiliatesLog';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'affiliatesLogID';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'referralID', 'campaignID', 'landingPage', 'referrer', 'useragent', 'ip', 'country', 'remoteHost', 'memberID'
	];
}
