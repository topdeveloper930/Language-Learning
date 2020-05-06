<?php

namespace App\Http\Controllers\Student;

use App\Components\Payment\Classes\PaymentGateway;
use App\Http\Controllers\StudentController;
use App\Transaction;
use App\TrialClassLog;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;
use Payment;

class PurchaseStudentController extends StudentController
{
	protected $js = [ 'stripe_sdk', 'student_purchase' ];

	protected $translation = 'student_purchase.js';

	protected function make()
	{
		parent::make();

		$this->setParams([
			'coupon_code'          => $this->getCouponCode(),
			'defaultPaymentMethod' => $this->getLastPaymentMethod( $this->user ),
			'defaults'             => $this->getDefaults()
		]);
	}

	protected function obtainData()
	{
		parent::obtainData();

		/**
		 * Payment here points to an instance of Driver that implements
		 * App\Components\Payment\Contracts\Payment interface.
		 *
		 * All job is done in the payment driver (see app/Components/Payment/Drivers)
		 * The driver name is taken from the input 'paymentMethod' field.
		 * The action passed in the route parameter 'id' ('balance' or 'send').
		 */
		abort_if( !in_array( $this->arguments[0], ['balance', 'send'] ), 400 );

		// If no paymentMethod param set, then it's balance request. Either driver fits.
		// Let's set Transfer with minimum extra configs and functionality.
		$this->data = Payment::gateway( request('paymentMethod', 'transfer' ) )
		                     ->{ $this->arguments[0] }();
	}

	private function getCouponCode()
	{
		return request(
			'coupon',
			Cookie::get(
				config('referral_program.referral_code_cookie_name'),
				$this->user->getReferralCouponCode()
			)
		);
	}

	private function getLastPaymentMethod( $user )
	{
		return request()->session()->remember(
			'paymentMethod',
			function() use ($user) {
				$tr = $user->transactions->where('paymentStatus', Transaction::COMPLETED)
				                               ->whereIn('paymentGateway', [ PaymentGateway::CREDIT_CARD, PaymentGateway::PAYPAL,
					                               PaymentGateway::BANK_TRANSFER, PaymentGateway::CHECK, PaymentGateway::STRIPE ])
				                               ->sortByDesc( 'transactionID' )
				                               ->first();

				$method = config('payment.default');

				if( $tr ) {
					switch ( $tr->paymentGateway ) {
						case PaymentGateway::CREDIT_CARD:
						case PaymentGateway::STRIPE:
							$method = 'stripe';
							break;
						case PaymentGateway::PAYPAL:
							$method = 'paypal';
							break;
						case PaymentGateway::BANK_TRANSFER:
							$method = 'transfer';
							break;
						case PaymentGateway::CHECK:
							$method = 'check';
					}
				}

				return $method;
			}
		);
	}

	private function getDefaults()
	{
		$param = Arr::get($this->arguments, 0);

		if( is_numeric( $param ) )
			$transaction = $this->user->transactions->find( $param );
		elseif ( is_string( $param ) )
			$transaction = $this->user->transactions()->where( 'paymentFor', 'LIKE', $param . '%' )->latest('transactionDate')->first();
		else
			$transaction = $this->user->transactions()->latest('transactionDate')->first();

		if( $transaction )
			return [
				'language'    => $transaction->getCourseLanguage(),
				'program'     => $transaction->getCourseType(),
				'hours'       => ( $transaction
					? $transaction->getHours()
					: config( 'payment.default_hours_purchase' ) ),
				'numStudents' => ( $transaction->purchase ? $transaction->purchase->numStudents : 1 ),
				'transactionID' => $this->arguments[ 0 ]
			];

		$trial = TrialClassLog::where('email', $this->user->getEmail() )->latest('trialClassLogID')->first();

		if( $trial )
			return [
				'language'    => ucfirst( $trial->language ),
				'program'     => trim( $trial->course, '-' ),
				'hours'       => config( 'payment.default_hours_purchase' ),
				'numStudents' => 1,
				'transactionID' => null
			];

		return [];
	}
}
