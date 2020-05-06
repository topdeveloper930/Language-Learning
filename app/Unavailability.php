<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unavailability extends Model
{
	/**
	 * The DB table name for the model.
	 *
	 * @var string
	 */
    protected $table = 'calendarUnavailable';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'calendarUnavailableID';


	public function teacher()
	{
		return $this->belongsTo( Teacher::class, 'teacherID', 'teacherID' );
	}

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'calendarUnavailableID'
	];

	public function ofTeacher($teacherID, $init_tz_code, $output_tz_code )
	{
		$return_arr = [];
		$periods = $this->where([ 'teacherID' => $teacherID ])->get();

		foreach ( $periods as $event )
			$this->mapUnavailability( $return_arr, $event, new \DateTimeZone( $init_tz_code ), new \DateTimeZone( $output_tz_code ) );


		$return_arr = $this->mergeIntersectingPeriods( $return_arr );

		return $this->mergeDays( $return_arr );
	}


	private function mapUnavailability( &$events, $event, $init_tz, $output_tz )
	{
		$dummyDate = date("Y-m-d ", strtotime("previous " . strtolower( $event[ 'day' ] ) ) );

		$dateStart = new \DateTime( $dummyDate . $event[ 'startTime' ], $init_tz );

		$dateStart->setTimezone( $output_tz );

		$dateEnd = new \DateTime( $dummyDate . $event[ 'endTime' ], $init_tz );
		$dateEnd->setTimezone( $output_tz );


		/*
		 * If due to time difference the event shifts over 2 days, then it's going to be 2 events, unless end is midnight.
		 * We will create a copy of the row for the second event and adjust start/end of both events over midnight.
		 */
		$dateEndTime = $dateEnd->format( 'H:i:s' );
		if(
			$dateEndTime <= $dateStart->format( 'H:i:s' )
			AND ( '00:00:00' != $dateEndTime OR intval( $dateEnd->diff( $dateStart )->format( '%d' ) ) > 0  )
		) {
			$ev = clone $event;

			$ev[ 'day' ] = [ (int) $dateStart->format( 'w' ) ];
			$ev[ 'startTime' ] = $dateStart->format( 'H:i:s' );
			$ev[ 'endTime' ] = '24:00:00';
			$event[ 'startTime' ] = '00:00:00';

			array_push( $events, $ev );
		}
		else {
			$event[ 'startTime' ]  = $dateStart->format( 'H:i:s' );
		}

		if( $dateEndTime == '00:00:00' ) {
			$event[ 'endTime' ] = '24:00:00';
			$w = $dateEnd->format( 'w' ) - 1;
			$w >= 0 OR $w = 6;
			$event[ 'day' ] = [ (int) $w ];
		}
		else {
			$event[ 'endTime' ] = $dateEndTime;
			$event[ 'day' ] = [ (int) $dateEnd->format( 'w' ) ];
		}

		array_push( $events, $event );
	}

	public function mergeIntersectingPeriods( $ev_arr )
	{
		// Nothing to merge
		if( count( $ev_arr ) < 2 )
			return $ev_arr;

		/**
		 * Loop through the array comparing the events for intersection starting from the end.
		 * If the pair intersects, then expand the one with smaller index, unset the other one and restart the process
		 * for the modified array.
		 * Otherwise (if no intersections occurred) return the input array.
		 */
		for( $i = count( $ev_arr) - 1; $i > 0; $i-- ) {
			for( $j = $i - 1; $j >= 0; $j-- ) {
				if(
					count( array_intersect( $ev_arr[ $j ][ 'day' ], $ev_arr[ $i ][ 'day' ] ) ) AND
					$ev_arr[ $j ][ 'startTime' ] <= $ev_arr[ $i ][ 'endTime' ] AND
					$ev_arr[ $j ][ 'endTime' ] >= $ev_arr[ $i ][ 'startTime' ]
				) {
					$ev_arr[ $j ] = static::mergeEvents( $ev_arr[ $j ], $ev_arr[ $i ] );
					unset( $ev_arr[ $i ] );
					sort( $ev_arr ); // To make indexes in row.

					return $this->mergeIntersectingPeriods( $ev_arr );
				}
			}
		}

		return  $ev_arr;
	}

	public function mergeEvents( $e1, $e2 )
	{
		if( $e1[ 'startTime' ] > $e2[ 'startTime' ] )
			$e1[ 'startTime' ] = $e2[ 'startTime' ];

		if( $e1[ 'endTime' ] < $e2[ 'endTime' ] )
			$e1[ 'endTime' ] = $e2[ 'endTime' ];

		return $e1;
	}

	private function mergeDays( $evArr )
	{
		// Nothing to merge
		if( count( $evArr ) < 2 )
			return $evArr;

		for( $i = count( $evArr) - 1; $i > 0; $i-- ) {
			for( $j = $i - 1; $j >= 0; $j-- ) {
				if(
					! empty( $evArr[ $i ] ) AND
					$evArr[ $i ][ 'startTime' ] == $evArr[ $j ][ 'startTime' ] AND
					$evArr[ $i ][ 'endTime' ] == $evArr[ $j ][ 'endTime' ]
				) {
					$evArr[ $j ][ 'day' ] = array_merge( $evArr[ $j ][ 'day' ], $evArr[ $i ][ 'day' ] );
					unset( $evArr[ $i ] );
					// Sort days
					$days = $evArr[ $j ][ 'day' ];
					sort( $days );
					$evArr[ $j ][ 'day' ] = $days;
					sort( $evArr ); // To make indexes in row.

					return $this->mergeDays( $evArr );
				}
			}
		}

		return $evArr;
	}
}
