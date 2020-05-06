<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CourseList extends Model
{
	protected $table = 'courseList';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'courseListID';

	public $timestamps = false;

	public function getCourseTitle()
	{
		return $this->language . '-' . $this->courseType;
	}

	public function getCost( $hours, $numStudents )
	{
		if( 'specialized3' == $this->payGrade ) {
			$totalCosts = $this->cost1;
		}
		else{
			if( $hours > 0 AND $hours <= 9 )
				$totalCosts = $this->cost1 * $hours;
			elseif( $hours > 9 AND $hours <= 19 )
				$totalCosts = $this->cost2 * $hours;
			elseif( $hours > 19 AND $hours <= 29 )
				$totalCosts = $this->cost3 * $hours;
			elseif($hours > 29 AND $hours <= 39 )
				$totalCosts = $this->cost4 * $hours;
			elseif( $hours > 39 AND $hours <= 49 )
				$totalCosts = $this->cost5 * $hours;
			elseif( $hours > 49 )
				$totalCosts = $this->cost6 * $hours;
		}

		if ( 2 == $numStudents )
			$totalCosts *= 1.5;
		elseif( 3 == $numStudents )
			$totalCosts *= 2;

		return $totalCosts;
	}

	public static function languages()
	{
		return Arr::flatten(
			static::groupBy('language')
		             ->get(['language'])
		             ->toArray()
		);
	}

	public static function assignedTo( $studentID, $teacherID )
	{
		return DB::table( 'courseList' )
		         ->join( 'teacher2student', 'courseList.courseType', '=', 'teacher2student.course' )
		         ->select( 'courseList.courseType' )
		         ->where( [
			         [ 'teacher2student.studentID', $studentID ],
			         [ 'teacher2student.teacherID', $teacherID ],
			         [ 'active', 1 ]
		         ] )->pluck('courseType');
	}
}
