<?php


namespace App\Rules;


use App\Event;
use App\Http\Requests\EventRequest;
use App\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EventRules extends Rule {

	public static function after( $attribute, $value, $parameters, $validator )
	{
		$tomorrow_gmt = gmdate ( "Y-m-d H:i:s", strtotime( $parameters[0] ) );

		$event_old = (isset($parameters[1])) ? Input::get($parameters[1]) : null;

		$validator->addReplacer('event_after', function($message, $attribute, $rule, $parameters){
			$hrs = preg_replace('$!\d|\+$', '', $parameters[0]);
			return str_replace([':hours'], trim( $hrs ), $message);
		});

		return $value >= $tomorrow_gmt AND ( !$event_old OR $event_old->eventStart >= $tomorrow_gmt);
	}

	/**
	 * We need to check 4 tables for overlap of the given period with those stored in the tables.
	 * We will make 4 queries and union them.
	 * Empty response means no conflict.
	 *
	 * @param string $attribute
	 * @param string $eventEnd
	 * @param array $parameters with [ 0 => eventStart, 1 => Event|calendarID|null, 2 => Teacher|teacherID|null ]
	 * @param Validator $validator
	 *
	 * @return bool
	 */
	public static function noConflict( $attribute, $eventEnd, $parameters, $validator )
	{
		$eventStart = Input::get($parameters[0]);
		$utc = 'UTC';
		$calendarID = null;

		$teacher = (isset($parameters[2])) ? Input::get($parameters[2]) : null;
		$event_old = (isset($parameters[1])) ? Input::get($parameters[1]) : null;

		if( $event_old )
			$calendarID = ( $event_old instanceof Event ) ? $event_old->calendarID : $event_old;

		if( $teacher AND !$teacher instanceof Teacher ){
			$teacher = Teacher::find( (int)$teacher );
		}
		elseif ( !$teacher AND $event_old ) {
			$event_old instanceof Event OR $event_old = Event::find( $event_old );
			$teacher = $event_old->teacher;
		}

		if( !$teacher )
			return false;

		$tz = $teacher->timezone_code();

		$start = Carbon::createFromFormat( EventRequest::DATE_FORMAT, $eventStart, $utc );
		$end = Carbon::createFromFormat( EventRequest::DATE_FORMAT, $eventEnd, $utc );

		$start->tz( $tz );
		$end->tz( $tz );

		$calendar_sql = DB::table( 'calendar' )
		                  ->select( 'calendarID as id' )
		                  ->where( [
			                  [ 'active', '=', '1' ],
			                  [ 'teacherID', '=', $teacher->teacherID ],
			                  [ 'eventStart', '<', $eventEnd ],
			                  [ 'eventEnd', '>', $eventStart ]
		                  ] );

		if ( $calendarID )
		{
			$calendar_sql->where( 'calendarID', '<>', $calendarID );
		}

		$trial_class_duration = config( 'main.trial_class_length', 30 );
		$trial_min = $trial_class_duration % 60;
		$trial_hrs = intval( $trial_class_duration / 60 );
		$trial_class_duration = sprintf('%d:%d:00', $trial_hrs, $trial_min );

		$trial_sql = DB::table( 'trialClass2Teachers' )
		               ->select( 'trialClassLogID as id' )
		               ->whereNotIn( 'results', [ 'cancelled', 'rescheduled', 'reassigned' ] )
		               ->where( [
			               [ 'teacherID', '=', $teacher->teacherID ],
			               [ 'teacherClassDate', '<', $end->format( 'Y-m-d H:i:s' ) ],
			               [ DB::raw( 'ADDTIME(`teacherClassDate`, "' . $trial_class_duration . '")' ), '>', $start->format( 'Y-m-d H:i:s' ) ]
		               ] );

		$unavailable_sql = DB::table( 'calendarUnavailable' )
		                     ->select( 'calendarUnavailableID as id' )
		                     ->where( 'teacherID', $teacher->teacherID )
		                     ->where( function ( $query ) use ( $start, $end ) {
		                     	$day_start = $start->format( 'l' );
		                     	$day_end = $end->format( 'l' );

			                     if ( $day_start == $day_end ) {
				                     $query->where( [
					                     [ 'day', '=', $day_start ],
					                     [ 'startTime', '<', $end->format( 'H:i:s' ) ],
					                     [ 'endTime', '>', $start->format( 'H:i:s' ) ]
				                     ] );
			                     }
			                     else {
				                     $query->where( [
					                     [ 'day', '=', $day_start ],
					                     [ 'startTime', '<', '24:00:00' ],
					                     [ 'endTime', '>', $start->format( 'H:i:s' ) ]
				                     ] )->orWhere( [
					                     [ 'day', '=', $day_end ],
					                     [ 'startTime', '<', $end->format( 'H:i:s' ) ],
					                     [ 'endTime', '>', '00:00:00' ]
				                     ] );
			                     }
		                     } );

		$timeOff_sql = DB::table('calendarTimeOff')
			->select('timeOffID as id')
			->where([
				['teacherID', '=', $teacher->teacherID],
				['active', '=', 1],
				['startDate', '<=', $end->format('Y-m-d')],
				['endDate', '>=', $start->format('Y-m-d')]
			]);

		return ! count(
			$calendar_sql->union( $trial_sql )
			             ->union( $unavailable_sql )
			             ->union( $timeOff_sql )
			             ->get()
		);
	}
}