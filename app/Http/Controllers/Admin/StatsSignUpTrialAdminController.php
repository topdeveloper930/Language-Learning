<?php

namespace App\Http\Controllers\Admin;

use App\CourseList;
use App\Http\Controllers\AdminController;
use App\LocationCountries;
use App\TrialClassLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Uses "legacy" template
 *
 * Class StatsSignUpTrialAdminController
 * @package App\Http\Controllers\Admin
 */
class StatsSignUpTrialAdminController extends AdminController
{
	protected $permitted_roles = [ 'super-admin', 'admin' ];

	protected $translation = 'stats_signup_trial';

	protected $current_menu = 'stats_signup_trial.header';
	protected $page_title = 'stats_signup_trial.header';

	protected function make()
	{
		parent::make();

		$this->tplConfig->addCSS( 'dataTables' );
		$this->tplConfig->addJS( [
			'charts-loader', 'jquery', 'dataTables', 'typeahead',
			'stats_signup_trial'
		] );

		$this->setParam( 'language_list', CourseList::languages());
	}

	protected function obtainData()
	{
		parent::obtainData();

		$validator = Validator::make( request()->all(), [
			'dataset' => 'required|in:stats,countries'
		]);

		/** @var $validator \Illuminate\Validation\Validator */
		if ( $validator->fails() )
			throw new ValidationException( $validator );

		if( 'stats' == request( 'dataset' ) ) {
			$year = (int) request( 'year', date( 'Y' ) );
			$country = request( 'country', 'USA' );
			$language = request( 'language', 'spanish' );
			$age = request( 'age', '' );
			$gender = request( 'gender', 0 );

			$this->data = TrialClassLog::getSignUpStats( $year, $country, $language, $age, $gender );
		}
		else {
			$this->data = LocationCountries::all()->pluck('name');
		}

	}
}
