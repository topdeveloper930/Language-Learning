<?php

namespace App;

use App\Traits\gCalendarTrait;
use App\Traits\Parseable;
use App\Traits\TimezoneTrait;
use App\Services\Auth\UserType;
use App\Services\Auth\UserTypeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Teacher extends Model implements UserType
{
	use TimezoneTrait, UserTypeTrait, gCalendarTrait, Parseable;

	const CREATED_AT = 'createDate';
	const UPDATED_AT = null;

	const ACTIVE    = 'Active';
	const INACTIVE  = 'Inactive';

	const TYPE = 'teacher';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'teacherID';

	protected $guard = 'teacher';

	protected $guarded = [];

	protected $visible = [ 'teacherID', 'title', 'firstName', 'lastName', 'countryResidence', 'timezone', 'profileImage',
		'teacherIntroduction', 'gender', 'languagesTaught', 'languagesSpoken', 'coursesTaught', 'agesTaught', 'hobbies',
		'countryOrigin', 'startedTeaching', 'gender' ];

	public function usuario()
	{
		return $this->belongsTo( User::class );
	}

	public function group()
	{
		return $this->belongsToMany( Group::class, 'group_teacher', 'teacher_id', 'group_id');
	}

	public function evaluations()
	{
		return $this->hasMany(Evaluation::class, 'teacherID', 'teacherID' );
	}

	public function students()
	{
		return $this->belongsToMany( Student::class, 'teacher2student', 'teacherID', 'studentID' );
	}

	public function group_history()
	{
		return $this->hasMany(GroupHistory::class, 'teacher_id', 'teacherID' );
	}

	public function unavailabilities()
	{
		return $this->hasMany(Unavailability::class, 'teacherID', 'teacherID' );
	}

	public function timeOff()
	{
		return $this->hasMany(TeacherTimeOff::class, 'teacherID', 'teacherID' );
	}

	public function classes()
	{
		return $this->hasMany(Event::class, 'teacherID', 'teacherID' );
	}

	public function classLogs()
	{
		return $this->hasMany(ClassLog::class, 'teacherID', 'teacherID' );
	}

	public function originCountry()
	{
		return $this->belongsTo(LocationCountries::class, 'countryOriginID' );
	}

	public function residenceCountry()
	{
		return $this->belongsTo(LocationCountries::class, 'countryResidenceID' );
	}

	public function originRegion()
	{
		return $this->belongsTo(LocationRegions::class, 'stateOriginID' );
	}

	public function residenceRegion()
	{
		return $this->belongsTo(LocationRegions::class, 'stateResidenceID' );
	}

	public function salary()
	{
		return $this->hasOne(TeacherSalary::class, 'teacherID', 'teacherID' );
	}

	public function salaryPayments()
	{
		return $this->hasMany(TeacherSalaryPayment::class, 'teacherID', 'teacherID' );
	}

	public function salaryComments()
	{
		return $this->hasMany(TeacherSalaryComment::class, 'teacherID', 'teacherID' );
	}

	public function trials2Teacher()
	{
		return $this->hasMany( TrialClass2Teacher::class, 'teacherID', 'teacherID' );
	}

	public function assignments()
	{
		return $this->hasMany( Teacher2Student::class, 'teacherID', 'teacherID' );
	}

	public function getByIDorEmail( $id_or_email, $is_email = false )
	{
		if ( $is_email ) {
			$t = $this->where( [ 'email' => $id_or_email ] )->get();
			return ( count( $t ) ) ? $t[ 0 ] : null;
		}

		return $this->find( $id_or_email );
	}

	public function addToGroup( $group_id )
	{
		$this->group->attach($group_id);
	}

	public function trialClasses( $start, $end )
	{
		return DB::table('trialClass2Teachers')
			->join( 'trialClassLog', 'trialClassLog.trialClassLogID', '=', 'trialClass2Teachers.trialClassLogID' )
			->select(
				'trialClass2Teachers.teacherID as teacherID',
				'trialClass2Teachers.teacherClassDate as start'
			)
			->where([
				[ 'trialClass2Teachers.teacherID', $this->teacherID ],
				[ 'trialClass2Teachers.teacherClassDate', '>=', $start ],
				[ 'trialClass2Teachers.teacherClassDate', '<=', $end ]
			])
			->whereNotIn( 'trialClass2Teachers.results', [ 'cancelled', 'rescheduled', 'reassigned' ] )
			->get();
	}

	public function listTrialClassesToLog()
	{
		$trialLength = config('main.trial_class_length');

		return DB::table('trialClass2Teachers')
		         ->join( 'trialClassLog', 'trialClassLog.trialClassLogID', '=', 'trialClass2Teachers.trialClassLogID' )
		         ->select(
			         'trialClass2Teachers.trialClass2Teachers as id',
			         DB::raw("CONCAT(trialClassLog.sex, ' ', trialClassLog.firstName, ' ', trialClassLog.lastName) as student"),
			         'trialClassLog.course as course',
			         'trialClass2Teachers.teacherClassDate as start'
		         )
		         ->where([
			         [ 'trialClass2Teachers.teacherID', $this->teacherID ],
			         [ 'trialClass2Teachers.results', '0' ]
		         ])
		         ->whereRaw( "CONVERT_TZ(DATE_ADD(trialClass2Teachers.teacherClassDate, INTERVAL {$trialLength} MINUTE), '{$this->timezone_code()}','UTC') < UTC_TIMESTAMP" )
		         ->get();
	}

	/**
	 * Total number of languages spoken.
	 * Checks native language (taught one) not listed under spoken languages.
	 * Assumes languages spoken separated by commas.
	 *
	 * @return int
	 */
	public function totalSpokenLanguages()
	{
		$native = $this->languagesTaught[0];
		$base = ( strlen( $this->languagesSpoken ) ) ? 1 : 0;

		if( strpos( $this->languagesSpoken, $native ) === false )
			$base++;

		return $base + substr_count($this->languagesSpoken, ",");
	}

	/**
	 * Total number of languages spoken.
	 * Checks native language (taught one) not listed under spoken languages.
	 * Assumes languages spoken separated by commas.
	 *
	 * @return int
	 */
	public function allSpokenLanguages()
	{
		$native = $this->languagesTaught[0];

		if( strpos( $this->languagesSpoken, $native ) === false )
			return $native . ' (Native), ' . $this->languagesSpoken;

		return $this->languagesSpoken;
	}

	/**
	 * Retrieves teacher's active lessons (paid and trial) that have not yet passed.
	 *
	 * @return mixed
	 */
	public function getUpcomingEvents()
	{
		$trialLength = config('main.trial_class_length');

		$union = DB::table( 'trialClass2Teachers' )
		           ->join( 'trialClassLog', 'trialClass2Teachers.trialClassLogID', '=', 'trialClassLog.trialClassLogID' )
		           ->select(
			           'trialClass2Teachers.trialClass2Teachers as id',
			           DB::raw("'' as studentID"),
			           DB::raw("CONCAT(trialClassLog.sex, ' ', trialClassLog.firstName, ' ', trialClassLog.lastName, ' (', trialClassLog.course, ')') as entryTitle"),
			           'trialClass2Teachers.teacherClassDate as start',
			           DB::raw( "DATE_ADD(trialClass2Teachers.teacherClassDate, INTERVAL {$trialLength} MINUTE) as end" )
		           )
		           ->where( [
			           [ 'trialClass2Teachers.results', 0 ],
			           [ 'trialClass2Teachers.teacherID', $this->getKey() ]
		           ] )
		           ->whereRaw( "CONVERT_TZ(DATE_ADD(trialClass2Teachers.teacherClassDate, INTERVAL {$trialLength} MINUTE), '{$this->timezone_code()}','UTC') > UTC_TIMESTAMP" );

		return DB::table( 'calendar' )
		         ->select(
			         'calendarID as id',
			         'studentID',
			         'entryTitle',
			         DB::raw("CONVERT_TZ(eventStart, 'UTC', '{$this->timezone_code()}') as start"),
			         DB::raw("CONVERT_TZ(eventEnd, 'UTC', '{$this->timezone_code()}') as end")
		         )
		         ->where( [
			         [ 'active', 1 ],
			         [ 'teacherID', $this->getKey() ]
		         ] )
		         ->whereRaw( "DATE_ADD(eventStart, INTERVAL ? MINUTE) > UTC_TIMESTAMP", [config('main.max_class_tolerance', 120)] )
		         ->union( $union )
		         ->get();
	}

	public function getStudentsWithCredits()
	{
		return DB::table('classCreditsLog')
			->join('students', 'classCreditsLog.studentID', '=', 'students.studentID')
			->join('teacher2student', function ($join) {
				$join->on('teacher2student.studentID', '=', 'classCreditsLog.studentID')
				     ->on('teacher2student.course', '=', 'classCreditsLog.course');
			})
			->leftJoin(
				DB::raw("(SELECT SUM(classLog.creditsUsed) credits, teacher2student.course course, classLog.studentID studentID, `classLog`.`numStudents` numStudents
					FROM classLog 
					    JOIN teacher2student ON classLog.studentID = teacher2student.studentID
		                                      AND teacher2student.course = SUBSTR(classLog.course, INSTR(classLog.course, '-') + 1)
					WHERE teacher2student.teacherID = {$this->getKey()} AND 1 = teacher2student.active AND classLog.active = 1
					GROUP BY course, studentID, numStudents) logged"
				),
				function ($join) {
					$join->on('classCreditsLog.studentID', '=', 'logged.studentID')
					     ->on('classCreditsLog.course', '=', 'logged.course')
					     ->on('classCreditsLog.numStudents', '=', 'logged.numStudents');
				}
			)
			->select(
				DB::raw("SUM(classCreditsLog.hours) - logged.credits as credits"),
				DB::raw("CONCAT(students.title, ' ', students.firstName, ' ', students.lastName) as student"),
				'classCreditsLog.course AS course',
				'classCreditsLog.numStudents as n',
				'classCreditsLog.studentID as id'
			)
			->where([
				['teacher2student.teacherID', $this->getKey()],
				['teacher2student.active', 1],
				['students.active', Student::STUDENT_ACTIVE]
			])
			->groupBy('student', 'course', 'n', 'id')
			->having('credits', '>', 0)
			->get();
	}

	public function getCoursesLimited($studentID, $course = null, $numStudents = null)
	{
		$q = DB::table('classCreditsLog')
			->leftJoin(
				DB::raw("(SELECT SUM(creditsUsed) AS creditsUsed, course, numStudents FROM classLog WHERE  active = 1 AND studentID = $studentID GROUP BY course, numStudents) cl"),
				function ($join) {
					$join->on(DB::raw("CONCAT(classCreditsLog.language, '-', classCreditsLog.course)"), '=', 'cl.course')
					     ->on('classCreditsLog.numStudents', '=', 'cl.numStudents');
				}
			)
			->leftJoin(
				'teacher2student',
				function ($join) {
					$join->on('teacher2student.studentID', '=', 'classCreditsLog.studentID')
					     ->on('teacher2student.course', '=', 'classCreditsLog.course')
					     ->where('teacher2student.active', 1);
				}
			)
			->select(
				'classCreditsLog.language AS language',
				'classCreditsLog.course AS course',
				'classCreditsLog.numStudents AS numStudents',
				DB::raw("SUM(classCreditsLog.hours) AS hours"),
				DB::raw("MAX(classCreditsLog.createDate) AS createDate"),
				DB::raw("IFNULL(cl.creditsUsed, 0) AS creditsUsed")
			)
			->where([
				['classCreditsLog.studentID', $studentID],
				['teacher2student.teacherID', $this->getKey()]
			]);

		if($course)
			$q->where('classCreditsLog.course', $course);

		if($numStudents)
			$q->where('classCreditsLog.numStudents', $numStudents);

		return $q->groupBy( [ 'language', 'course', 'numStudents', 'creditsUsed' ] )
		         ->orderBy( 'createDate', 'desc' )
		         ->get();
	}

	/*
	* Accessors and Mutators
	*/

	public function getLanguagesTaughtAttribute( $value )
	{
		return $this->parseStringToArray( $value, 'specialty' );
	}

	public function setLanguagesTaughtAttribute( $value )
	{
		$this->attributes['languagesTaught'] = $this->urlEncodeArray( $value, 'specialty' );
	}

	public function getDialectsTaughtAttribute( $value )
	{
		return $this->parseStringToArray( $value, 'dialect' );
	}

	public function setDialectsTaughtAttribute( $value )
	{
		$this->attributes['dialectsTaught'] = $this->urlEncodeArray( $value, 'dialect' );
	}

	public function getCoursesTaughtAttribute( $value )
	{
		return $this->parseStringToArray( $value, 'courses' );
	}

	public function setCoursesTaughtAttribute( $value )
	{
		$this->attributes['coursesTaught'] = $this->urlEncodeArray( $value, 'courses' );
	}

	public function getAgesTaughtAttribute( $value )
	{
		return $this->parseStringToArray( $value, 'ages' );
	}

	public function setAgesTaughtAttribute( $value )
	{
		$this->attributes['agesTaught'] = $this->urlEncodeArray( $value, 'ages' );
	}

	/* end Accessors and Mutators */

	public static function listActive( $language = '' )
	{
		return Cache::remember(
			'teachersListActive' . $language,
			24 * 60,
			function () use( $language ) {

				$query = DB::table('teachers')
					->leftJoin('locationCountries as origin', 'teachers.countryOriginID', '=', 'origin.id')
					->leftJoin('locationCountries as residence', 'teachers.countryResidenceID', '=', 'residence.id')
					->select(
						'teacherID',
						'title',
						'firstName',
						'lastName',
						'languagesSpoken',
						'coursesTaught',
						'agesTaught',
						'teacherIntroduction',
						'profileImage',
						'origin.code as countryCode',
						'origin.name as countryName',
						'residence.id as residenceID',
						'residence.name as residenceName'
					)
					->where('teachers.activeTeacher', static::ACTIVE)
					->where('teachers.newStudents', 1)
					->where('teachers.lastName', '!=', '(Test User)');

				if( $language )
					$query->where('teachers.languagesTaught', 'LIKE', "%specialty%5B%5D=$language%");

				return $query->get();
			}
		);
	}

	public static function countOriginCountriesForLanguage( $language )
	{
		return Cache::remember(
			'countOriginCountriesFor' . $language,
			24 * 60,
			function () use( $language ) {
				return static::where([
					['activeTeacher', static::ACTIVE],
					['newStudents', 1],
					['languagesTaught', 'LIKE', "%specialty%5B%5D=$language%"],
				])->distinct()->count('countryOrigin');
			}
		);
	}
}
