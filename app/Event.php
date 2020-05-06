<?php

namespace App;

use App\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;

class Event extends Model implements Syncable
{
	const CREATED_AT = 'createDate';

	const ACTIVE    = 1;
	const INACTIVE  = 0;

	protected $table = 'calendar';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'calendarID';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'teacherID', 'studentID', 'entryTitle', 'entryType', 'eventStart', 'eventEnd', 'active', 'createdBy', 'updated_by',
		'numStudents'
	];

	protected $visible = [
		'calendarID', 'teacherID', 'studentID', 'entryTitle', 'entryType', 'eventStart', 'eventEnd', 'active', 'numStudents',
		'teacher_name'
	];

	protected $appends = [ 'teacher_name'];

	public function student()
	{
		return $this->belongsTo( Student::class, 'studentID', 'studentID' );
	}

	public function teacher()
	{
		return $this->belongsTo( Teacher::class, 'teacherID', 'teacherID' );
	}

	public function teacher_gcalendar()
	{
		return $this->belongsTo( CalendarExternal::class, 'teacherID', 'user_id' )
		            ->where( 'user_type', 'teacher' )
		            ->where( 'provider', 'google' );
	}

	public function student_gcalendar()
	{
		return $this->belongsTo( CalendarExternal::class, 'studentID', 'user_id' )
		            ->where( 'user_type', 'student' )
		            ->where( 'provider', 'google' );
	}

	public function getCourseType()
	{
		$first_parenthesis = strpos( $this->entryTitle, '(' ) + 1;
		return substr( $this->entryTitle, $first_parenthesis, strpos( $this->entryTitle, ')' ) - $first_parenthesis );
	}

	public function lengthHours()
	{
		return ( strtotime( $this->eventEnd ) - strtotime( $this->eventStart ) ) / 3600;
	}

	public function getTeacherNameAttribute()
	{
		return $this->teacher->fullname();
	}

	/**
	 * @param array $extras with additional parameters - something like:
	 *                      'reminders' => [
	 *							'useDefault' => FALSE,
	 *							'overrides' => [
	 *							    ['method' => 'email', 'minutes' => 24 * 60],
	 *							    ['method' => 'popup', 'minutes' => 10],
	 *						    ],
	 *					    ]
	 *
	 * @return \Google_Service_Calendar_Event
	 */
	public function asGoogleEvent( $isStudent = true, $extras = [] )
	{
		$summary = ( $isStudent )
			? config( 'api.gcalendar.student_class_pref' ) . substr( $this->entryTitle, strpos( $this->entryTitle, ' (' ) )
			: $this->entryTitle;

		$data = [
			'id'        => $this->calendarID,
			'summary'   => $summary,
			'status'    => 'confirmed',
			'location'  => config( 'api.gcalendar.location' ),
			'start'     => [
				'dateTime' => str_replace(' ', 'T', $this->eventStart ) . 'Z'
			],
			'end'       => [
				'dateTime' => str_replace(' ', 'T', $this->eventEnd ) . 'Z'
			]
		];

		return new \Google_Service_Calendar_Event( array_merge( $data, ( array ) $extras ) );
	}

	public function sync()
	{
		$action = 'insert';

		if( !$this->active )
			$action = 'delete';
		elseif ( $this->updated_by )
			$action = 'patch';

		// Sync both teacher's and student's calendars, if integrated and token provided.
		// After syncing implemented in frontend (if), here one of the below actions can be omitted.
		if( $this->teacher_gcalendar )
			$this->teacher_gcalendar->syncSingleEvent( $this->asGoogleEvent( false ), $action );

		if( $this->student_gcalendar )
			$this->student_gcalendar->syncSingleEvent( $this->asGoogleEvent(), $action );
	}
}
