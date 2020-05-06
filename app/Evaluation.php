<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Evaluation extends Model
{
	const CREATED_AT = 'evaluationDate';
	const UPDATED_AT = null;

    protected $table = 'evaluation';

    protected $primaryKey = 'evaluationID';

	protected $guarded = [];

	public function teacher()
	{
		return $this->belongsTo( Teacher::class, 'teacherID', 'teacherID' );
	}

    public function student()
    {
    	return $this->belongsTo( Student::class, 'studentID', 'studentID' );
    }

    public static function studentReports( $studentID, $teacherID = null )
    {
    	$query = DB::table( 'evaluation' )
		    ->join( 'teachers', 'evaluation.teacherID', '=', 'teachers.teacherID')
		    ->select(
		    	'teachers.title as title',
			    DB::raw("CONCAT(teachers.firstName, ' ', teachers.lastName) as teacher"),
			    'teachers.profileImage as photo',
			    'evaluation.*'
		    )
		    ->where( 'studentID', $studentID );

    	if( $teacherID ) $query->where( 'evaluation.teacherID', $teacherID );

    	return $query->get();
    }
}
