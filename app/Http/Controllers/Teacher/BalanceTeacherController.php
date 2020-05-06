<?php

namespace App\Http\Controllers\Teacher;

use App\ClassLog;
use App\Http\Controllers\TeacherController;
use App\TeacherSalaryPayment;
use Illuminate\Support\Facades\Validator;

class BalanceTeacherController extends TeacherController
{
	protected $js = [ 'jquery3_4', 'dataTables', 'teacher_balance' ];

	protected $translation = 'teacher_balance.js';

	protected function obtainData()
	{
		parent::obtainData();

		abort_unless(method_exists( $this, $method = request()->method() ), 404);

		$this->data = $this->{$method}();
	}

	private function get()
	{
		if(
			!empty($this->arguments[0])
			AND is_string( $this->arguments[0] )
		    AND strpos($this->arguments[0], '-') !== false
		) {
			list( $year, $month ) = explode( '-', $this->arguments[0] );

			return ( $year == date('Y') AND $month == trans('calendar_common.months')[ date('n') - 1 ] )
				? ClassLog::teacherNotPaidClasses( $this->teacher->getPK() )
				: $this->getPreviousPayments( $year, $month );
		}
		else {
			return $this->adminChanges();
		}
	}

	private function post()
	{
		Validator::make(
			[
				'comments' => request( 'comments' ),
				'date'     => (int) date( 'd' )
			],
			[
				'comments' => 'nullable|string|max:5000',
				'date'     => 'numeric|max:' . config( 'main.teacher_can_comment_salary_till' )
			],
			[ 'date.max' => __( 'teacher_balance.comment_edit_date' ) ]
		)->validate();

		$salaryComment = $this->teacher->salaryComments()->where( [
			[ 'year', date( 'Y', strtotime( '-1 month' ) ) ],
			[ 'month', date( 'F', strtotime( '-1 month' ) ) ]
		] )->first();

		if ( $salaryComment AND $salaryComment->update( [ 'comments' => request( 'comments' ) ] ) )
		{
			return __( 'teacher_balance.comment_updated' );
		}
		elseif ( $this->teacher->salaryComments()->create( [
			'comments' => request( 'comments' ),
			'year'     => date( 'Y', strtotime( '-1 month' ) ),
			'month'    => date( 'F', strtotime( '-1 month' ) )
		] ) )
		{
			return __( 'teacher_balance.comment_added' );
		}

		abort( 400 );
	}

	private function adminChanges()
	{
		return $this->teacher->salaryPayments()
		                     ->whereRaw( 'YEAR(enteredDate) = YEAR(CURRENT_DATE()) AND MONTH(enteredDate) = MONTH(CURRENT_DATE())' )
		                     ->where( 'course',  'Admin Change' )
		                     ->first();
	}

	private function getPreviousPayments( $year, $monthName )
	{
		if( $monthName ) {
			// The payments are calculated and recorded next month.
			$indx = array_search( $monthName, trans('calendar_common.months') ) + 2;

			if( $indx > 12 ) {
				$indx = 1;
				$year++;
			}
		}
		else {
			$indx = $monthName;
		}

		return TeacherSalaryPayment::teacherCompletedClasses( $this->teacher->getPK(), $year, $indx );
	}
}
