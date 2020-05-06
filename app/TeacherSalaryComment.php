<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherSalaryComment extends Model
{
	const CREATED_AT = null;
	const UPDATED_AT = 'editDate';

	protected $table = 'teacherSalaryComments';

	protected $primaryKey = 'teacherSalaryCommentsID';

	protected $fillable = ['month', 'year', 'comments'];

	public function teacher()
	{
		return $this->belongsTo( Teacher::class, 'teacherID', 'teacherID' );
	}
}
