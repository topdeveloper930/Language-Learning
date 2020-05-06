<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeacherTimeOff extends Model
{
	/**
	 * The DB table name for the model.
	 *
	 * @var string
	 */
    protected $table = 'calendarTimeOff';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'timeOffID';


	public function teacher()
	{
		return $this->belongsTo( Teacher::class, 'teacherID', 'teacherID' );
	}
}
