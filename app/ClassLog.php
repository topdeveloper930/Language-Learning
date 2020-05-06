<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ClassLog extends Model
{
	const CREATED_AT = 'recordedDate';
	const UPDATED_AT = null;

	protected $table = 'classLog';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'classLogID';

	protected $fillable = [
		'studentID', 'teacherID', 'numStudents', 'course', 'creditsUsed', 'whatWasStudied', 'internalNotes', 'classDate'
	];

	public function student()
	{
		return $this->belongsTo( Student::class, 'studentID', 'studentID' );
	}

	public function teacher()
	{
		return $this->belongsTo( Teacher::class, 'teacherID', 'teacherID' );
	}

	public function studentCompletedClasses( $student_id )
	{
		return DB::table( $this->table )
			->leftJoin( 'teachers', $this->table . '.teacherID', '=', 'teachers.teacherID' )
			->select(
				$this->table . '.teacherID as teacherID',
				$this->table . '.numStudents as numStudents',
				$this->table . '.course as course',
				$this->table . '.creditsUsed as creditsUsed',
				$this->table . '.whatWasStudied as topics',
				$this->table . '.classDate as date',
				DB::raw('CONCAT(`teachers`.`firstName`, " ", `teachers`.`lastName`) as teacher'),
				'teachers.title as title',
				'teachers.profileImage as photo'
			)
			->where( $this->table . '.studentID', $student_id )
			->orderBy( 'course', 'DESC' )
			->orderBy( 'date', 'DESC' )
			->get();
	}

	public static function teacherNotPaidClasses( $teacher_id )
	{
		return DB::table( 'classLog' )
		         ->leftJoin( 'students', 'classLog' . '.studentID', '=', 'students.studentID' )
		         ->leftJoin( 'courseList', function ( $join ) {
			         $join->on( DB::raw( "SUBSTRING_INDEX(classLog.course, '-', -1)" ), '=', 'courseList.courseType' );
		         } )
		         ->select(
			         'classLog' . '.numStudents as numStudents',
			         'classLog' . '.course as course',
			         DB::raw( "SUM(classLog.creditsUsed) as hours" ),
			         DB::raw( 'CONCAT(`students`.`firstName`, " ", `students`.`lastName`) as student' ),
			         'courseList.payGrade as payGrade'
		         )
		         ->where( [
			         [ 'classLog' . '.teacherID', $teacher_id ],
			         [ 'classLog' . '.processPay', 0 ],
			         [ 'classLog' . '.active', 1 ]
		         ] )
		         ->groupBy( 'student', 'course', 'numStudents' )
		         ->get();
	}

	/**
	 * @param int    $studentID
	 * @param string $courseOrLanguage the course type or language. Using the param containing '%' makes the function to
	 *                                 use LIKE operator. If it starts with % (position 0), then it's courseType without
	 *                                 language prefix (like '%Standard Spanish'). Trailing '%' means language
	 *                                 (like 'Spanish%') and would result in summing up all hours for the language
	 *                                 disregarding the course type. Absence of '%' means usage of '=' (equals)
	 *                                 operator and assumes full course name provided (like 'Spanish-Standard Spanish')
	 * @param int    $numStudents      not considered if null
	 *
	 * @return mixed
	 */
	public static function totalStudentHoursByCourse($studentID, $courseOrLanguage, $numStudents = null)
	{
		$op = '=';

		if(false !== strpos($courseOrLanguage, '%'))
			$op = 'LIKE';

		$where = [
			['studentID', $studentID],
			['active', 1],
			['course', $op, $courseOrLanguage]
		];

		if($numStudents)
			array_push($where, ['numStudents', $numStudents]);

		return (float) DB::table('classLog')
			->select(DB::raw('SUM(creditsUsed) as creditsUsed'))
			->where($where)
			->value('creditsUsed');
	}
}
