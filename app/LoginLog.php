<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
	const CREATED_AT = 'loginDate';
	const UPDATED_AT = null;

	protected $table = 'loginLog';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'loginLogID';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'loginType', 'userID', 'browser'
	];
}
