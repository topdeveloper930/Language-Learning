<?php

namespace App;

use App\Traits\CheckAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Transaction extends Model
{
	use CheckAttribute;

	const CREATED_AT = null;
	const UPDATED_AT = 'transactionDate';

	const PENDING   = 'Pending';
	const COMPLETED = 'Completed';
	const DENIED    = 'Denied';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'transactionID';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'userID', 'paymentGateway', 'gatewayTransID', 'paymentType', 'paymentAmount', 'paymentFee', 'paymentFor',
		'hours', 'paymentStatus', 'emailUsed', 'receiverEmail', 'referrer', 'referrerCommission'
	];

	public function student()
	{
		return $this->belongsTo( Student::class, 'userID', 'studentID' );
	}

	public function referral()
	{
		return $this->belongsTo( Referral::class, 'userID', 'id' );
	}

	public function referralCredits()
	{
		return $this->hasMany( ReferralCredit::class, 'transactionID', 'transactionID' );
	}

	public function referralCreditCharge()
	{
		return $this->referralCredits()->where('amount', '<', 0);
	}

	public function purchase()
	{
		return $this->hasOne( Purchase::class, 'transactionID', 'transactionID' );
	}

	public function classCreditsLog()
	{
		return $this->hasOne( ClassCreditsLog::class, 'transactionID', 'transactionID' );
	}

	public function giftCardLog()
	{
		return $this->hasOne( GiftCardLog::class, 'transactionID', 'transactionID');
	}

	public function coupon_usage()
	{
		return $this->hasOne( CouponsUsed::class, 'transactionID', 'transactionID');
	}

	public function getCourseLanguage()
	{
		if( $this->hasAttribute( 'paymentFor' ) )
			return substr( $this->paymentFor, 0, strpos( $this->paymentFor, '-' ) );

		return $this->purchase->course->language;
	}

	public function getCourseType()
	{
		if( $this->hasAttribute( 'paymentFor' ) )
			return substr( $this->paymentFor, strpos( $this->paymentFor, '-' ) + 1 );

		return $this->purchase->courseType;
	}

	public function getHours()
	{
		if( $this->hasAttribute( 'hours' ) )
			return $this->hours;

		return $this->purchase->hours;
	}

	public static function via_API( $userID, $for, $hours )
	{
		$config = config( 'api.transaction' );

		return static::create([
			'userID'             => $userID,
			'paymentGateway'     => $config['gateway'],
			'gatewayTransID'     => 'usuario:' . \Illuminate\Support\Facades\Auth::id(),
			'paymentType'        => 'API',
			'paymentAmount'      => 0.00,
			'paymentFee'         => $config['paymentFee'],
			'paymentFor'         => $for,
			'hours'              => $hours,
			'paymentStatus'      => static::COMPLETED,
			'emailUsed'          => '',
			'receiverEmail'      => $config['receiverEmail'],
			'referrer'           => '',
			'referrerCommission' => 0
		]);
	}

	/**
	 * Monthly or weekly transaction stats.
	 * The value is cached for 1 day.
	 *
	 * @param integer $year
	 * @param string $by_period
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public static function getTransactionStats( $year, $by_period )
	{
		$value = Cache::remember(
			'transaction_stats_' . $by_period . $year,
			24 * 60,
			function () use( $year, $by_period ) {
				$stats_data = DB::table('transactions')
				                ->select(
					                DB::raw('SUM(`paymentAmount`) as sum'),
					                DB::raw('COUNT(`transactionID`) as transactions_cnt'),
					                DB::raw("SUBSTR(`paymentFor`, 1, POSITION('-' IN `paymentFor`) - 1) AS `language`"),
					                DB::raw("$by_period(`transactionDate`) AS `date`")
				                )
				                ->where( 'paymentStatus', static::COMPLETED );

				if( 'MONTH' == $by_period ) {
					$stats_data->whereRaw( "YEAR(`transactionDate`) = '$year'" );
				}
				else {
					$next_year = $year + 1;
					$stats_data->whereRaw( "YEARWEEK(`transactionDate`) >= {$year}01 AND YEARWEEK(`transactionDate`) < {$next_year}01" );
				}
				return $stats_data->groupBy( 'language', 'date' )->get();
			}
		);

		return $value;
	}

	public static function pendingPayments()
	{
		return DB::table('transactions')
			->join( 'students', 'transactions.userID', '=', 'students.studentID' )
			->join( 'purchases', 'transactions.transactionID', '=', 'purchases.transactionID' )
			->select(
				'transactions.transactionID as id',
				'transactions.userID as studentID',
				'transactions.gatewayTransID as gateway_id',
				DB::raw("CONCAT(`students`.`firstName`, ' ', `students`.`lastName`) as name"),
				'transactions.paymentGateway as gateway',
				'transactions.paymentAmount as amount',
				'transactions.transactionDate as date',
				'purchases.courseType as course',
				'purchases.numStudents as numStudents',
				'purchases.hours as hours'
			)
			->where( 'paymentStatus', static::PENDING )
			->get();
	}
}
