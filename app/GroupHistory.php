<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupHistory extends Model
{
	protected $table = 'group_history';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'group_id', 'usuario_id', 'teacher_id', 'student_id', 'action', 'note'
	];

	public function usuario()
	{
		return $this->belongsTo( User::class );
	}

	public function group()
	{
		return $this->belongsTo( Group::class, 'group_id', 'groupID' );
	}

	public function teacher()
	{
		return $this->belongsTo( Teacher::class, 'teacher_id', 'teacherID' );
	}

	public function student()
	{
		return $this->belongsTo( Student::class, 'student_id', 'studentID' );
	}
}
