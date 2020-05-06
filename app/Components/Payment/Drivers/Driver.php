<?php


namespace App\Components\Payment\Drivers;


use App\Affiliate;
use App\Components\Payment\Classes\PaymentDefinitions;
use App\Components\Payment\Classes\PaymentType;
use App\Components\Payment\Contracts\Payment;
use App\Coupon;
use App\CourseList;
use App\GiftCard;
use App\Notifications\PaymentConfirmation;
use App\Notifications\PaymentNoTeacher;
use App\Purchase;
use App\Referral;
use App\ReferralCredit;
use App\Student;
use App\Traits\ArrayObjectAccess;
use App\Traits\ClassNames;
use App\Transaction;
use App\TrialClassLog;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

abstract class Driver implements Payment {

	use ArrayObjectAccess, ClassNames;

	protected $paymentType = PaymentType::MANUAL;

	/**
	 * @var Student
	 */
	protected $user;

	/**
	 * @var \Illuminate\Http\Request
	 */
	protected $request;

	/**
	 * @var GiftCard
	 */
	protected $giftCard;

	/**
	 * @var Coupon
	 */
	protected $coupon;

	/**
	 * Specific driver configs
	 *
	 * @var array|null
	 */
	protected $config;

	protected $sessionKey = PaymentDefinitions::SESSION_KEY;

	public function __construct( $user, Request $request, $config = null )
	{
		$this->user    = $user;
		$this->request = $request;
		$this->config  = $config;

		$this->setFields();
	}

	/**
	 * @inheritDoc
	 */
	public function balance()
	{
		$this->processInput();

		$this->storeDataToSession();

		return $this->balance;
	}

	/**
	 * @inheritDoc
	 */
	public function send()
	{
		$this->getDataFromSession();

		$this->request->session()->put( 'paymentMethod', strtolower( $this->classShortName() ) );

		abort_if( !array_key_exists( 'balance', $this->container ), 422, __('student_purchase.missing_calculation') );

		$this->setReferrer();

		$this->transaction = $this->createTransaction();

		// This sets transactionID field in the container for creating a purchase instance.
		$this->transactionID = $this->transaction->getKey();

		$this->createPurchase();

		$this->applyBonuses();

		$this->deleteReferralCookie();

		$this->afterSend();

		return [
			'redirectUrl' => route( 'students', [ 'controller' => 'purchase-result', 'id' => $this->transaction->getKey() ])
		];
	}

	/**
	 * @inheritDoc
	 */
	public function confirm()
	{
		$this->retrieveData();

		$this->handleReferrer();

		$this->logClassCredit();

		$this->setTransactionCompleted();

		$this->updatePurchase();

		$this->assignTeacher();

		$this->afterPaymentConfirmation();

		$this->clearSession();
	}

	/**
	 * @inheritDoc
	 */
	public function cancel( $reason = null )
	{
		if( $reason )
			$this->offsetSet( 'reasonOfDenial', $reason );

		$this->retrieveData();

		$this->rollbackBonuses();

		$this->setTransactionDenied();

		$this->updatePurchase();

		$this->clearSession();
	}

	public function instantCheckout()
	{
		$action = $this->getAction();

		if( $action )
			$this->{$action}();
	}

	public function webhook() {}

	public function withData( $data )
	{
		$data = (array) $data;

		$new_inst = clone $this;

		foreach ( $data as $key => $datum )
			$new_inst->{$key} = $datum;

		return $new_inst;
	}

	public function withTransaction( Transaction $transaction )
	{
		$new_inst = clone $this;

		$new_inst->offsetSet( 'transaction', $transaction );

		return $new_inst;
	}

	public function units( $amount = null )
	{
		!is_null( $amount ) OR $amount = 1;

		return $amount * Arr::get( $this->config, 'units', 1 );
	}

	public function setCacheKey( $key )
	{
		$this->cacheKey = $key;
	}

	protected function createPurchase()
	{
		$this->purchase = Purchase::create( $this->container );
	}

	/**
	 * Retrieves data from DB by transaction ID
	 *
	 * @param int|Transaction $transactionOrID
	 */
	public function retrieveData()
	{
		abort_if( !$this->transaction, 400, __( 'student_purchase.no_data' ));

		$this->purchase OR $this->purchase = $this->transaction->purchase;
		$this->user OR $this->user = $this->transaction->student;
		$this->balance = $this->purchase->balance;

		abort_if( !$this->purchase, 400, __( 'student_purchase.no_data' ));
	}

	public function clearSession()
	{
		if( $this->sessionKey AND $this->request->hasSession() )
			$this->request->session()->forget( $this->sessionKey );
	}

	public function rollbackBonuses()
	{
		$this->deleteCouponUsage();

		$this->deleteReferralCreditsCharge();

		$this->deleteGiftCardCharge();
	}

	protected function applyBonuses()
	{
		$this->useCoupon();

		$this->chargeReferralCredits();

		$this->useGiftCard();
	}

	protected function createTransaction()
	{
		return Transaction::create([
			'userID'             => $this->user->studentID,
			'paymentGateway'     => $this->paymentGateway,
			'gatewayTransID'     => $this->getGatewayTransID(),
			'paymentType'        => $this->paymentType,
			'paymentAmount'      => $this->getPaymentAmount(),
			'paymentFee'         => $this->getPaymentFee(),
			'paymentFor'         => $this->getCourse()->getCourseTitle(),
			'course_id'          => $this->getCourse()->getKey(),
			'hours'              => $this->offsetGet( 'hours' ),
			'paymentStatus'      => Transaction::PENDING,
			'emailUsed'          => $this->user->getEmail(),
			'receiverEmail'      => $this->receiverEmail(),
			'referrer'           => $this->getReferrerCode(),
			'referrerCommission' => $this->getReferrerCommission()
		]);
	}

	protected function getPaymentAmount()
	{
		return $this->balance['total'];
	}

	protected function getPaymentFee()
	{
		return 0;
	}

	protected function receiverEmail()
	{
		return config( 'legacy.globals.mainEmail' );
	}

	/**
	 * The referrer stored in the `trialClassLog` table (affiliate) and `referrals` table.
	 *
	 * @return string
	 */
	protected function getReferrerCode()
	{
		return $this->offsetGet( 'referrer_code' );
	}

	protected function getReferrerCommission()
	{
		$ref_code = $this->getReferrerCode();

		return ( !$ref_code OR strpos( $ref_code, config('referral_program.referral_coupon_pref') ) === 0 )
			? 0
			: $this->getAffiliateCommission( $ref_code );
	}

	protected function getAffiliateCommission( $ref_code )
	{
		$dash_indx = strpos( $ref_code, '-' );
		$code = ( !$dash_indx ) ? $ref_code : substr( $ref_code, 0, $dash_indx);

		$affiliate = Affiliate::where([['affiliateCode', $code], ['active', Affiliate::ACTIVE]])->first();

		return ( $affiliate ) ? $affiliate->commission : 0;
	}

	protected function chargeReferralCredits()
	{
		if( $this->balance['referral_credits'] ) {
			$this->user->referralCredits()->create([
				'amount' => - floatval( $this->balance[ 'referral_credits' ] ),
				'transactionID' => $this->transaction->getKey()
			]);
		}
	}

	protected function deleteReferralCreditsCharge()
	{
		if( $this->balance[ 'referral_credits' ] AND $charge = $this->transaction->referralCreditCharge->first() )
			$charge->delete();
	}

	protected function getGatewayTransID()
	{
		return (string) $this->gatewayTransID;
	}

	protected function deleteReferralCookie()
	{
		Cookie::queue( Cookie::forget( config('referral_program.referral_code_cookie_name') ) );
	}

	protected function handleReferrer()
	{
		if( $this->transaction->referrer
		    AND $referral = Referral::find( $this->transaction->userID )
		        AND !$referral->realized
			AND $coupon = Coupon::where([['active', Coupon::ACTIVE], ['code', $this->transaction->referrer]])->first()
				AND $coupon->is_referral() ) {
			ReferralCredit::create([
				'owner_id' => $coupon->studentID,
				'amount' => $coupon->getBonusAmount( $this->balance['total'] + $this->balance['giftcard'] ),
				'coupon_id' => $coupon->id,
				'transactionID' => $this->transaction->getKey()
			]);
		}
	}

	protected function logClassCredit()
	{
		$this->transaction->classCreditsLog()->updateOrCreate([
			'studentID'   => $this->transaction->userID,
			'process'     => 'hoursAdded',
			'language'    => $this->getCourse()->language,
			'course'      => $this->purchase->courseType,
			'numStudents' => $this->purchase->numStudents,
			'hours'       => $this->purchase->hours
		]);
	}

	protected function useCoupon()
	{
		if( $this->offsetGet( 'coupon_code' ) ) {
			Coupon::where( 'code', $this->offsetGet( 'coupon_code' ) )
			      ->first()
			      ->usages()
			      ->create([
				      'studentID'     => $this->transaction->userID,
				      'transactionID' => $this->transaction->getKey()
			      ]);
		}
	}

	protected function deleteCouponUsage()
	{
		if( $this->purchase->coupon AND  $this->transaction->coupon_usage )
			$this->transaction->coupon_usage->delete();

	}

	protected function useGiftCard()
	{
		if( !$this->offsetGet( 'giftcard_code' ) ) return;

		$this->transaction->giftCardLog()->create([
			'studentID'   => $this->user->getPK(),
			'code'        => $this->offsetGet( 'giftcard_code' ),
			'amount'      => $this->balance[ 'giftcard' ],
			'paidFor'     => $this->getCourse()->getCourseTitle()
		]);
	}

	protected function deleteGiftCardCharge()
	{
		if( $this->balance[ 'giftcard' ] AND $this->transaction->giftCardLog )
			$this->transaction->giftCardLog->delete();
	}

	protected function setFields() {}

	protected function afterSend() {}

	protected function afterPaymentConfirmation()
	{
		$student = Student::find( $this->transaction->userID );

		$teachers = $student->teachers()
		                    ->wherePivot( 'active', '=', 1 )
		                    ->wherePivot( 'course', '=', $this->purchase->courseType )
		                    ->get();

		$coordinator = \App\User::where('email', config( 'legacy.globals.mainEmail' ))->first();

		$teachers_count = count( $teachers );

		$notification = ( $teachers_count )
			? new PaymentConfirmation( $this->transaction, $teachers )
			: new PaymentNoTeacher( $this->transaction );

		$student->notify( $notification );

		$coordinator->notify( $notification );

		foreach ( $teachers as $teacher )
			$teacher->notify( $notification );
	}

	protected function setTransactionCompleted()
	{
		$this->transaction->paymentStatus = Transaction::COMPLETED;
		$this->transaction->paymentType = $this->getPaymentType();
		$this->transaction->save();
	}

	protected function assignTeacher()
	{
		$emails = [ $this->transaction->student->email ];

		if( $this->transaction->student->paypalEmail )
			array_push( $emails, $this->transaction->student->paypalEmail );

		if (
			! Student::find( $this->transaction->userID )
		              ->teachers()
		              ->wherePivot( 'course', $this->purchase->courseType )
		              ->wherePivot( 'active', 1 )
		              ->count()
			AND $teacherID = TrialClassLog::getIdOfTeacherAssignedToTrialClass( $this->purchase->courseType, $emails )
		) {
			$this->transaction->student->teachers()->attach(
				$teacherID,
				[ 'language' => $this->getCourse()->language, 'course' => $this->purchase->courseType ]
			);
		}
	}

	protected function setTransactionDenied()
	{
		try {
			$this->performServiceCancellation();
		}
		catch ( \Exception $ex ) {
			Log::error(
				'Cannot cancel the transaction on the service provider side (' . $this->paymentGateway . '): ' . $ex->getMessage(),
				$ex->getTrace()
			);
		}

		$this->transaction->paymentStatus = Transaction::DENIED;
		$this->transaction->paymentType = $this->paymentType;
		$this->transaction->save();
	}

	protected function performServiceCancellation() {}

	protected function updatePurchase()
	{
		if( $this->description )
			$this->purchase->description = $this->description;

		$this->purchase->result = $this->paymentInfo;
		$this->purchase->error = $this->getReasonOfDenial();
		$this->purchase->save();
	}

	protected function getReasonOfDenial()
	{
		return $this->reasonOfDenial;
	}

	protected function setReferrer()
	{
		$this->offsetSet(
			'referrer_code',
			$this->request->cookie(
				config( 'referral_program.affiliate_cookie_name', 'll_referral' ),
				(string) $this->user->getReferrerCode()
			)
		);
	}

	protected function getAction()
	{
		return '';
	}

	/**
	 * @return CourseList
	 */
	protected function getCourse()
	{
		if( !$this->course OR $this->courseType != $this->course->courseType ) {
			if( $this->purchase ) {
				$this->course = $this->purchase->course;
			}
			elseif ( $this->transaction ) {
				$this->purchase = $this->transaction->purchase;
				$this->course = $this->purchase->course;
			}
			else {
				$this->course = CourseList::where('courseType', $this->courseType)->first();
			}
		}

		return $this->course;
	}

	protected function getPaymentType()
	{
		return $this->paymentType;
	}

	private function processInput( $data = null )
	{
		$data OR $data = $this->request->all();

		$this->validateData( $data );

		$this->offsetSet( 'balance', $this->calculateBalance() );
	}

	private function storeDataToSession()
	{
		if( $this->sessionKey AND $this->request->session() )
			$this->request->session()->put( $this->sessionKey, $this->container );
	}

	private function getDataFromSession( $sessionKey = null )
	{
		$sessionKey = $sessionKey ?: $this->sessionKey;

		if( $sessionKey )
			$this->container = $this->request->session()->get( $sessionKey, [] );
	}



	private function validateData( $data )
	{
		$validator = Validator::make($data, [
			'courseType'    => 'bail|required|max:50|exists:courseList',
			'hours'         => 'required|numeric|min:.25',
			'numStudents'   => 'required|integer|min:1|max:3',
			'coupon_code'   => 'nullable|string|max:16|exists:coupons,code',
			'giftcard_code' => 'nullable|string|max:48|exists:giftCards,code'
		]);

		$giftCard =& $this->giftCard;
		$coupon =& $this->coupon;


		$validator->after(function ($validator) use( &$giftCard, &$coupon ) {
			$coupon_code = Arr::get( $validator->attributes(), 'coupon_code' );
			$giftcard_code = Arr::get( $validator->attributes(), 'giftcard_code' );

			if(
				$coupon_code
				AND !$validator->getMessageBag()->has( 'coupon_code' )
				    AND (! ( $coupon = Coupon::where('code', $coupon_code )->first() )
				         OR !$coupon->canBeUsedBy( $this->user ))
			)
				$validator->getMessageBag()->add('coupon_code', __( 'validation.code', ['attribute' => 'coupon', 'values' => request('coupon_code') ]));

			if(
				$giftcard_code
				AND !$validator->getMessageBag()->has( 'giftcard_code' )
				    AND !($giftCard = GiftCard::where([ ['code', $giftcard_code], ['amountRemaining', '>', 0]])->first())
			)
				$validator->getMessageBag()->add('giftcard_code', __( 'validation.code', ['attribute' => 'giftcard', 'values' => request('giftcard_code') ]));
		});

		$validator->validate();

		return $this->container = $validator->valid();
	}

	private function calculateBalance()
	{
		$balance = [];
		$balance[ 'discount' ] = $balance[ 'referral_credits' ] = $balance[ 'giftcard' ] = 0;

		$balance[ 'total' ] =
		$balance[ 'cost' ] = $this->getCourse()->getCost( $this->hours, $this->numStudents );

		// Coupon
		if( $this->coupon ) {
			$balance[ 'discount' ] = $balance[ 'total' ];

			$balance[ 'coupon' ] = $this->coupon->formatPar();
			$balance[ 'total' ] = $this->coupon->apply( $balance[ 'total' ] );
			$balance[ 'discount' ] -= $balance[ 'total' ];
		}

		// Referral credits
		$balance[ 'referral_credits' ] = $balance[ 'total' ];
		$balance[ 'total' ] = $this->user->applyReferralCredits( $balance[ 'total' ] );
		$balance[ 'referral_credits' ] -= $balance[ 'total' ];

		// Gift card
		if( $this->giftCard ) {
			$balance[ 'giftcard' ] = $balance[ 'total' ];

			$balance[ 'total' ] = $this->giftCard->apply( $balance[ 'total' ] );
			$balance[ 'giftcard' ] -= $balance[ 'total' ];
		}

		return $balance;
	}
}