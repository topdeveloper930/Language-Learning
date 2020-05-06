<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SnAccount extends Model
{
	const UPDATED_AT = null;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'provider', 'provider_uid'
	];

	public function user()
	{
		return $this->morphTo();
	}

	public function authInst()
	{
		return ('App\Student' == $this->user_type OR !$this->user )
			? $this->user
			: $this->user->usuario;
	}
}
