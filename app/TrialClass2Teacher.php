<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrialClass2Teacher extends Model
{
	const CREATED_AT = 'assignDate';
	const UPDATED_AT = null;

	const NOSHOW = 'noshow';
	const COMPLETED = 'completed';

	protected $table = 'trialClass2Teachers';

	protected $primaryKey = 'trialClass2Teachers';

	public function teacher()
	{
		return $this->belongsTo(Teacher::class, 'teacherID', 'teacherID');
	}

	public function trialClassLog()
	{
		return $this->belongsTo( TrialClassLog::class, 'trialClassLogID', 'trialClassLogID' );
	}
}
