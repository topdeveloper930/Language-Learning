<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeacherSalaryPayment extends Model
{
	const CREATED_AT = 'enteredDate';
	const UPDATED_AT = null;

	protected $table = 'teacherSalaryPayments';

	protected $primaryKey = 'teacherSalaryPaymentsID';

	public function teacher()
	{
		return $this->belongsTo( Teacher::class, 'teacherID', 'teacherID' );
	}

	public function student()
	{
		return $this->belongsTo( Student::class, 'studentID', 'studentID' );
	}

	public static function teacherCompletedClasses( $teacher_id, $year = null, $month = null )
	{
		$q = DB::table( 'teacherSalaryPayments' )
		         ->leftJoin( 'students', 'teacherSalaryPayments' . '.studentID', '=', 'students.studentID' )
		         ->leftJoin( 'courseList', function ( $join ) {
			         $join->on( DB::raw( "SUBSTRING_INDEX(teacherSalaryPayments.course, '-', -1)" ), '=', 'courseList.courseType' );
		         } )
		         ->select(
			         'teacherSalaryPayments' . '.numStudents as numStudents',
			         'teacherSalaryPayments' . '.course as course',
			         DB::raw( "SUM(teacherSalaryPayments.hoursTaught) as hours" ),
			         DB::raw( 'CONCAT(`students`.`firstName`, " ", `students`.`lastName`) as student' ),
			         'courseList.payGrade as payGrade',
			         DB::raw( "SUM(teacherSalaryPayments.totalPay) as totalPay" )
		         )
		         ->where( 'teacherSalaryPayments' . '.teacherID', $teacher_id );

		if( $year )
			$q->whereYear( 'enteredDate', $year );

		if( $month )
			$q->whereMonth( 'enteredDate', $month );

		return $q->groupBy( 'student', 'course', 'numStudents' )
		         ->get();
	}
}
