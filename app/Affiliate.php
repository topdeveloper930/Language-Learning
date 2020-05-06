<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
	const CREATED_AT = 'createDate';
	const UPDATED_AT = null;

	const ACTIVE    = 1;
	const INACTIVE  = 0;

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'affiliateID';

	protected $guarded = [ 'affiliateID' ];
}
