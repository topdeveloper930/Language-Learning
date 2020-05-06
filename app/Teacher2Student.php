<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher2Student extends Model
{
	const CREATED_AT = 'assignDate';
	const UPDATED_AT = null;

    protected $table = 'teacher2student';

    protected $primaryKey = 't2sID';

    protected $fillable = [
    	'teacherID', 'studentID', 'language', 'course', 'active'
    ];

    public function teacher()
    {
    	return $this->belongsTo( Teacher::class, 'teacherID', 'teacherID' );
    }

	public function student()
	{
		return $this->belongsTo( Student::class, 'studentID', 'studentID' );
	}

	public function courseFromList()
	{
		return $this->belongsTo( CourseList::class, 'course', 'courseType' );
	}
}
