<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassCreditsLog extends Model
{
	protected $table = 'classCreditsLog';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'classCreditsLogID';

	public $timestamps = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'studentID', 'transactionID', 'process', 'language', 'course', 'numStudents', 'hours', 'notes'
	];

	protected $visible = [
		'classCreditsLogID', 'studentID', 'transactionID', 'process', 'language', 'course', 'hours'
	];

	public function student()
	{
		return $this->belongsTo( Student::class, 'studentID', 'studentID' );
	}

	public function transaction()
	{
		return $this->belongsTo( Transaction::class, 'transactionID', 'transactionID' );
	}
}
