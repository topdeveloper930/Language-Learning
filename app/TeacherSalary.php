<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherSalary extends Model
{
	const CREATED_AT = 'createDate';
	const UPDATED_AT = null;

    protected $table = 'teacherSalary';

    protected $primaryKey = 'teacherSalaryID';

    public function teacher()
    {
    	return $this->belongsTo( Teacher::class, 'teacherID', 'teacherID' );
    }
}
