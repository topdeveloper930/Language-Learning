<?php

namespace App;

use App\Traits\gCalendarTrait;
use App\Traits\TimezoneTrait;
use App\Notifications\MailResetPasswordToken;
use App\Services\Auth\UserType;
use App\Services\Auth\UserTypeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Student extends \Illuminate\Foundation\Auth\User implements UserType
{
	use UserTypeTrait, TimezoneTrait, gCalendarTrait;

	const CREATED_AT = 'createDate';
	const UPDATED_AT = null;

	const TYPE = 'student';

	const API_TYPE = 'api';

	const STUDENT_ACTIVE                = 'Active';
	const STUDENT_INACTIVE              = 'Inactive';
	const CLASS_REMINDER_ACTIVE         = 'Active';
	const CLASS_REMINDER_INACTIVE       = 'Inactive';
	const CLASS_LOG_MESSAGES_ACTIVE     = 'Active';
	const CLASS_LOG_MESSAGES_INACTIVE   = 'Inactive';
	const MAILING_LIST_ACTIVE           = 'Active';
	const MAILING_LIST_INACTIVE         = 'Inactive';

	protected $guard = 'student';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'studentID';

	protected $password_field_name = 'password';

	protected $guarded = ['password', 'password128'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'title', 'firstName', 'lastName', 'email', 'password', 'password128', 'accountType', 'dateOfBirth', 'information', 'timezone',
		'skype', 'phone', 'paypalEmail', 'country', 'state', 'city', 'studentNotes', 'mailingList', 'classLogMessages',
		'classReminder'
	];

	/**
	 * Set here default values for student created via API.
	 * @var array
	 */
	private $api_specific_fields = [
		'mailingList'       => 'Inactive',
		'classLogMessages'  => 'Inactive',
		'classReminder'     => 'Inactive',
		'accountType'       => 'api'
	];

	public function group()
	{
		return $this->belongsToMany(Group::class, 'group_student', 'student_id', 'group_id');
	}

	public function teachers()
	{
		return $this->belongsToMany( Teacher::class, 'teacher2student', 'studentID', 'teacherID' );
	}

	public function group_history()
	{
		return $this->hasMany(GroupHistory::class, 'student_id' );
	}

	public function classes()
	{
		return $this->hasMany(Event::class, 'studentID', 'studentID' );
	}

	public function classCreditLogs()
	{
		return $this->hasMany(ClassCreditsLog::class, 'studentID', 'studentID' );
	}

	public function classLogs()
	{
		return $this->hasMany(ClassLog::class, 'studentID', 'studentID' );
	}

	public function evaluations()
	{
		return $this->hasMany(Evaluation::class, 'studentID', 'studentID' );
	}

	public function transactions()
	{
		return $this->hasMany( Transaction::class, 'userID', 'studentID' );
	}

	public function addToGroup( $usuario_id, $group_id, $selfDeleteOnFailure = true )
	{
		try {
			$g = Group::find( $group_id );

			if( !$g->attachStudent( $usuario_id, $this->studentID ) )
				throw new \RuntimeException('Cannot add student to group' );
		}
		catch ( \Exception $e ) {
			!$selfDeleteOnFailure OR $this->delete();
			throw $e;
		}
	}

	public function assignments()
	{
		return $this->hasMany( Teacher2Student::class, 'studentID', 'studentID' );
	}

	public function coupons()
	{
		return $this->hasMany( Coupon::class, 'studentID', 'studentID' );
	}

	public function refCoupon()
	{
		return $this->coupons()->where([
			['active', Coupon::ACTIVE],
			['code', 'LIKE', config('referral_program.referral_coupon_pref') . '%']
		])->first();
	}

	public function referral()
	{
		return $this->hasOne( Referral::class, 'id', 'studentID' );
	}

	public function referrals()
	{
		return $this->refCoupon()->referrals()->get();
	}

	public function getReferrerCode()
	{
		return DB::table('coupons')
			->join('referrals', 'referrals.coupon_id', '=', 'coupons.id')
			->select('coupons.code')
			->where('referrals.id', '=', $this->getKey())
			->value('code');
	}

	public function referralInvitations()
	{
		return $this->hasMany( ReferralInvitation::class, 'referrer_id', 'studentID');
	}

	public function referralInvitationsTodayTotal()
	{
		return $this->referralInvitations()->whereRaw( 'DATE(`created_on`) = CURDATE()' )->count();
	}

	public function belongsToGroupOfUser( $usuario_id )
	{
		return $this->group->count() AND $this->group->where('usuario_id', '=', $usuario_id)->count();
	}

	public function referralCredits()
	{
		return $this->hasMany( ReferralCredit::class, 'owner_id', 'studentID' );
	}

	public function remainingCredits( $course, $numStudents = 1 )
	{
		$credits_ttl = (float) $this->classCreditLogs
									->where('numStudents', $numStudents)
									->where('course', $course)
									->sum('hours');

		$scheduled = (float) DB::table('calendar')
		                       ->select(DB::raw('SUM(TIMESTAMPDIFF(MINUTE, `eventStart`, `eventEnd`) / 60) AS `hours`'))
		                       ->where([
			                       ['entryType', '=', 'Student'],
			                       ['entryTitle', 'LIKE', "%$course)"],
			                       ['studentID', '=', $this->studentID],
			                       ['active', '=', Event::ACTIVE],
			                       ['numStudents', '=', $numStudents],
			                       ['eventStart', '>=', DB::raw('CURDATE()')]
		                       ])->first()->hours;;

		$credits_used = (float) DB::table( 'classLog' )
								  ->select( DB::raw( 'SUM(`creditsUsed`) AS `hours`' ) )
								  ->where([
									  ['course', 'LIKE', "%$course"],
									  ['studentID', $this->studentID],
									  ['numStudents', '=', $numStudents]
								  ])->first()->hours;

		return $credits_ttl - $credits_used - $scheduled;
	}

	public function applyReferralCredits( $amount )
	{
		$amount -= ReferralCredit::remaining( $this->getPrimaryKey() );

		$amount > 0 OR $amount = 0;

		return round($amount, 2);
	}

	public function courseTitle( $courseType )
	{
		return $this->accost() . " ($courseType)";
	}

	/**
	 * @param array $data
	 *
	 * @return Student|Model
	 */
	public function createWithAutoFill( $data )
	{
		$stub = array_fill_keys( [
			'title', 'firstName', 'lastName', 'email', 'password', 'password128', 'dateOfBirth', 'information', 'timezone',
			'skype', 'phone', 'paypalEmail', 'country', 'state', 'city', 'studentNotes'
		], '' );

		return $this->create( array_merge( $stub, $this->api_specific_fields, $data ) );
	}

	/**
	 * {@inheritDoc}
	 * @see \Illuminate\Contracts\Auth\Authenticatable::getAuthIdentifierName()
	 */
	public function getAuthIdentifierName()
	{
		return "email";
	}

	public function saveNewPassHash( $pass_hash )
	{
		$this->password = $pass_hash;
		$this->save();
	}


	/**
	 * @param string $course
	 * @param int $numStudents
	 *
	 * @return array
	 */
	public function getCoursesLimited( $course = '', $numStudents = null )
	{
		$creditsUsed = "(SELECT SUM(creditsUsed) AS creditsUsed, course, numStudents FROM classLog WHERE  active = 1 AND studentID = {$this->studentID} GROUP BY course, numStudents)  cl";

		$scheduled = "(
			SELECT SUM(TIMESTAMPDIFF(MINUTE, eventStart, eventEnd) / 60) AS hours, numStudents, TRIM(')' FROM SUBSTR(entryTitle, LOCATE('(', entryTitle) + 1)) AS course 
			FROM calendar
			WHERE entryType = 'Student' AND studentID={$this->studentID} AND active=1 AND eventStart >= CURDATE() GROUP BY course, numStudents
		) sch";

		$query = DB::table( 'classCreditsLog' )
		           ->leftJoin( DB::raw( $creditsUsed ), function( $join )
		           {
			           $join->on( DB::raw( "CONCAT(classCreditsLog.language, '-', classCreditsLog.course)" ), '=', 'cl.course')
			                ->on( 'classCreditsLog.numStudents', '=', 'cl.numStudents');
		           })
		           ->leftJoin( DB::raw( $scheduled ), function( $join )
		           {
			           $join->on( 'sch.course', '=', 'classCreditsLog.course')
			                ->on( 'sch.numStudents', '=', 'classCreditsLog.numStudents');
		           })
		           ->select(
			           'classCreditsLog.language as language',
			           'classCreditsLog.course as course',
			           'classCreditsLog.numStudents as numStudents',
			           DB::raw( 'SUM(classCreditsLog.hours) as hours' ),
			           DB::raw( 'MAX(classCreditsLog.createDate) as createDate' ),
			           DB::raw( 'IFNULL(cl.creditsUsed, 0) AS creditsUsed' ),
			           DB::raw( 'IFNULL(cl.creditsUsed, 0) AS creditsUsed' ),
			           DB::raw( 'IFNULL(sch.hours, 0.0) AS scheduled' )
		           )
		           ->where( 'classCreditsLog.studentID', '=', $this->studentID);

		if( $numStudents )
			$query->where( 'classCreditsLog.numStudents', '=', $numStudents );

		if($course)
			$query->where( 'classCreditsLog.course', '=', $course );

		$query->groupBy( 'language', 'course', 'numStudents', 'cl.creditsUsed', 'scheduled' )
		      ->orderBy( 'createDate', 'DESC' );

		return $query->get();
	}

	/**
	 * Unlike getCoursesLimited takes into account only logged classes and do not sum scheduled
	 * (but not yet logged) classes.
	 *
	 * @param string $course
	 * @param int $numStudents
	 */
	public function getCourseRemainingBalance($course, $numStudents)
	{
		$q = DB::table('classLog')
			->select(DB::raw("-1 * SUM(creditsUsed) AS hours"))
			->where([
				['active', 1],
				['studentID', $this->getKey()],
				['course', 'LIKE', "%$course"],
				['numStudents', $numStudents]
			]);

		return array_sum(DB::table('classCreditsLog')
			->select(
				DB::raw("SUM(hours) AS hours")
			)
			->union($q)
			->where([
				['studentID', $this->getKey()],
				['course', $course],
				['numStudents', $numStudents]
			])
			->pluck('hours')->toArray());
	}

	public function progressReports( $teacherID = null )
	{
		return Evaluation::studentReports( $this->getKey(), $teacherID );
	}

	public function getEmailForPasswordReset() {
		return $this->email;
	}

	/**
	 * Send the password reset notification.
	 *
	 * @param  string  $token
	 * @return void
	 */
	public function sendPasswordResetNotification($token)
	{
		$this->notify( new MailResetPasswordToken( $token, 'students' ) );
	}

	public static function getSignups( $year, $by_period )
	{
		$value = Cache::remember(
			'signups_stats_' . $by_period . $year,
			24 * 60,
			function () use( $year, $by_period ) {
				$students_signup = DB::table('students')
					->leftJoin( 'trialClassLog', 'trialClassLog.email', '=', 'students.email' )
					->select(
						DB::raw("$by_period(`createDate`) AS `date`"),
						DB::raw("COUNT(`studentID`) AS `cnt`"),
						DB::raw("CONCAT(UCASE(LEFT(trialClassLog.language, 1)), SUBSTRING(trialClassLog.language, 2)) AS `language`")
					);

				if( 'MONTH' == $by_period ) {
					$students_signup->whereRaw("YEAR(`createDate`) = $year");
				}
				else {
					$next_year = $year + 1;
					$students_signup->whereRaw("YEARWEEK(`createDate`) >= {$year}01 AND YEARWEEK(`createDate`) < {$next_year}01");
				}

				return $students_signup->groupBy( 'language', 'date' )->get();
			}
		);

		return $value;
	}

	public function getReferralsWithBonuses()
	{
		return DB::table( 'students' )
	           ->join( DB::raw( 'referrals' ), function( $join )
	           {
		           $join->on( DB::raw('FIND_IN_SET(students.studentID, referrals.id)'), '!=', DB::raw('0'));
	           })
				->join( 'coupons', 'coupons.id', '=', 'referrals.coupon_id')
				->leftJoin('transactions', 'transactions.userID', '=', 'referrals.id')
				->leftJoin('referral_credits', 'transactions.transactionID', '=', 'referral_credits.transactionID')
	           ->select(
		           'students.studentID as studentID',
		           DB::raw( 'CONCAT(students.firstName, " ", students.lastName) as referral' ),
		           DB::raw( 'MAX(referral_credits.amount) as bonus' )
	           )
	           ->where( 'referrals.coupon_id', '=', $this->refCoupon()->id )
	           ->groupBy('studentID', 'referral')
	           ->get();
	}

	public function getReferralCreditsHistory()
	{
		return DB::table( 'referral_credits' )
		         ->leftJoin( DB::raw( 'transactions as my_t' ), function( $join )
		         {
			         $join->on( 'my_t.transactionID', '=', 'referral_credits.transactionID')
			              ->on( 'my_t.userID', '=', 'referral_credits.owner_id');
		         })
		         ->leftJoin( DB::raw( 'transactions as r_t' ), function( $join )
		         {
			         $join->on( 'r_t.transactionID', '=', 'referral_credits.transactionID')
			              ->on( 'r_t.userID', '!=', 'referral_credits.owner_id');
		         })
		         ->leftJoin('students', 'students.studentID', '=', 'r_t.userID')
		         ->select(
			         'referral_credits.*',
			         DB::raw( 'CONCAT(students.firstName, " ", students.lastName) as referral_name' ),
			         'my_t.paymentFor AS course'
		         )
		         ->where( 'referral_credits.owner_id', '=', $this->studentID )
		         ->get();
	}



	public function invitedFriends()
	{
		return DB::table( 'referral_invitations' )
		         ->leftJoin('students', 'students.email', '=', 'referral_invitations.email')
		         ->leftJoin('referrals', 'students.studentID', '=', 'referrals.id')
		         ->select(
			         'referral_invitations.name',
			         'referral_invitations.email',
			         DB::raw('DATE(referral_invitations.created_on) as date'),
			         DB::raw( 'COUNT(students.studentID) as signed_up' ),
			         'referrals.realized AS has_purchase'
		         )
		         ->where( 'referral_invitations.referrer_id', '=', $this->studentID )
		         ->groupBy( 'referral_invitations.email', 'students.studentID')
		         ->get();
	}

	public function getReferralCouponCode( $not_realized_only = true )
	{
		return DB::table( 'coupons' )
		         ->leftJoin( 'referrals', 'referrals.coupon_id', '=', 'coupons.id' )
		         ->select( 'coupons.code' )
		         ->where( 'referrals.id', $this->getPrimaryKey() )
		         ->when( $not_realized_only, function( $query ) {
					$query->where( 'referrals.realized', 0 );
		         })
		         ->value('code');
	}
}
