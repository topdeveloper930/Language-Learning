<?php

namespace App\Http\Controllers\Admin;

use App\CourseList;
use App\Http\Controllers\AdminController;
use App\ReportActiveStudent;
use App\Student;
use App\Transaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Uses "legacy" template
 *
 * Class StatsStudentAveragePurchaseAdminController
 * @package App\Http\Controllers\Admin
 */
class StatsStudentAveragePurchaseAdminController extends AdminController
{
	protected $permitted_roles = [ 'super-admin', 'admin' ];

	protected $translation = 'stats_student_average_purchase';

	protected $current_menu = 'stats_student_average_purchase.header';
	protected $page_title = 'stats_student_average_purchase.header';

	protected function make()
	{
		parent::make();

		$this->tplConfig->addCSS( 'dataTables' );
		$this->tplConfig->addJS( [
			'jquery', 'dataTables',
			'student_average_purchase_stats'
		] );

		$this->setParam( 'language_list', CourseList::languages());
	}

	protected function obtainData()
	{
		parent::obtainData();

		$validator = Validator::make( request()->all(), [
			'dataset' => 'required|in:signups,active,transactions',
			'year'    => 'integer',
			'mode'    => 'in:month,week'
		]);

		/** @var $validator \Illuminate\Validation\Validator */
		if ( $validator->fails() )
			throw new ValidationException( $validator );

		$year = (int) request( 'year', date( 'Y' ) );
		$mode = strtoupper( request( 'mode', 'month' ) );

		switch ( request( 'dataset', 'transactions' ) ) {
			case 'signups':
				$this->data = Student::getSignups( $year, $mode );
				break;
			case 'active': // Only monthly stats available
				$this->data = ReportActiveStudent::activeStudentsByLanguage( $year );
				break;
			case 'transactions':
				$this->data = Transaction::getTransactionStats( $year, $mode );
				break;
			default:
				throw new \BadMethodCallException();
		}
	}
}
