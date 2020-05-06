<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobApplicant extends Model
{
	const CREATED_AT = 'createDate';
	const UPDATED_AT = null;

	protected $table = 'jobApplicant';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'jobApplicantID';

	protected $guarded = [ 'jobApplicantID', 'createDate' ];
}
