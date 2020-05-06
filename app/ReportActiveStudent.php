<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReportActiveStudent extends Model
{
	const CREATED_AT = 'createDate';
	const UPDATED_AT = null;

	/**
	 * The DB table name for the model.
	 *
	 * @var string
	 */
	protected $table = 'reportActiveStudents';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'reportActiveStudentsID';

	protected $visible = [];
	protected $guarded = [ 'reportActiveStudentsID' ];

	/**
	 * The cron task is run on first of the next month. Hence we need to correct the date (minus one month) to get right one.
	 * @param $year
	 *
	 * @return mixed
	 */
	public static function activeStudentsByLanguage( $year )
	{
		$tbl = ( new static() )->getTable();

		$value = Cache::remember(
			'active_students_stats_' . $year,
			24 * 60,
			function () use( $tbl, $year ) {

				return DB::table( $tbl )
				         ->join( 'courseList', 'courseList.courseType', '=', $tbl . '.course' )
				         ->select(
					         DB::raw( "MONTH( DATE_SUB(`createDate`, INTERVAL 1 MONTH) ) AS `date`" ),
					         DB::raw( "SUM(`total`) AS `cnt`" ),
					         'language as language'
				         )
				         ->whereRaw( "YEAR(DATE_SUB(`createDate`, INTERVAL 1 MONTH)) = $year" )
				         ->groupBy( 'date', 'language' )->get();
			}
		);

		return $value;
	}
}
