<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coupon extends Model
{
	const CREATED_AT = 'created_on';
	const UPDATED_AT = null;

	const ACTIVE    = 1;
	const INACTIVE  = 0;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'code', 'description', 'active', 'amount', 'percent', 'purchase_type', 'students_limit', 'limit_per_student',
		'start_on', 'expire_on', 'studentID'
	];

	protected $hidden = null;

	public function referrals()
	{
		return $this->belongsToMany( Student::class, 'referrals', 'coupon_id', 'id', 'studentID' )
		            ->withPivot('realized');
	}

	public function usages()
	{
		return $this->hasMany( CouponsUsed::class );
	}

	public function referralCredits()
	{
		return $this->hasMany( ReferralCredit::class );
	}

	public function is_referral()
	{
		return ( strpos( $this->code, config('referral_program.referral_coupon_pref') ) === 0 );
	}

	public function apply( $amount )
	{
		if( $this->amount )
			$amount -= $this->amount;
		elseif ( $this->percent )
			$amount *= 1 - $this->percent / 100;

		$amount > 0 OR $amount = 0;

		return round($amount, 2);
	}

	/**
	 * @param Student|int $student Student entity or id (studentID)
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function canBeUsedBy ( $student )
	{
		return static::studentCanUse( $this->code, $student );
	}

	public function formatPar()
	{
		return ( $this->amount )
			? '$' . number_format( $this->amount, 2 )
			: number_format( $this->percent, 2 ) . '%';
	}

	public function getBonusAmount( $amount )
	{
		return ( config('referral_program.referrer_bonus_amount') )
			? config('referral_program.referrer_bonus_amount')
			: config('referral_program.referrer_bonus_percent') * $amount / 100;
	}

	/**
	 *
	 * @param             $code
	 * @param Student|int $student
	 *
	 * @return bool
	 */
	public static function studentCanUse( $code, $student )
	{
		if ( !$code OR !$student )
			return false;

		$student instanceof Student OR $student = Student::find($student);

		$tz = $student ? $student->timezone_code() : null;
		$tz OR $tz = 'UTC';

		try {
			$tz = new \DateTimeZone( $tz );
		}
		catch( \Exception $e ) {
			$tz = new \DateTimeZone('UTC');
		}

		$studentID = $student ? $student->studentID : null;

		$now = Carbon::now($tz)->format('Y-m-d H:i:s');

		return (bool) DB::table( 'coupons' )
		         ->leftJoin( 'coupons_used', 'coupons_used.coupon_id', '=', 'coupons.id' )
		         ->leftJoin( 'transactions', 'coupons_used.studentID', '=', 'transactions.userID' )
		         ->select( 'coupons.code' )
		         ->where([
			         [ 'coupons.code', $code ],
			         [ 'coupons.active', static::ACTIVE ],
			         [ 'coupons.start_on', '<=', $now ],
			         [ 'coupons.expire_on', '>=', $now ]
		         ])
		         ->whereRaw( '(ISNULL(coupons.studentID) OR coupons.studentID != ?)', [$studentID])
		         ->whereRaw( "coupons.purchase_type IN (IF((SELECT COUNT(*) FROM transactions WHERE userID = ? AND paymentStatus != ?) > 0, 'recurrent', 'new'), 'all')", [$studentID, 'Denied'])
		         ->whereRaw( 'coupons.students_limit > (SELECT COUNT( DISTINCT studentID ) FROM coupons_used WHERE coupon_id = coupons.id AND studentID != ?)', [$studentID])
		         ->whereRaw( 'coupons.limit_per_student > (SELECT COUNT( * ) FROM coupons_used WHERE studentID = ? AND coupon_id = coupons.id)', [$studentID])
		         ->distinct()
		         ->count();
	}
}