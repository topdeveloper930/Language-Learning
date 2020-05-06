<?php

namespace App\Http\Controllers\Admin;

use App\Components\Payment\Classes\PaymentGateway;
use App\Http\Controllers\AdminController;
use App\Purchase;
use App\Transaction;
use Illuminate\Support\Facades\Validator;
use Payment;

class PaymentsPendingAdminController extends AdminController
{
	protected $permitted_roles = [ 'super-admin', 'admin' ];

	protected $translation = 'admin_payments_pending.js';

	protected $js = [ 'jquery', 'dataTables', 'admin_payments_pending' ];

	public function make()
	{
		$this->tplConfig->addCSS([ 'dataTables' ]);
	}

	/**
	 * Handle ajax requests by method.
	 * I.e. GET request processed by get() method, PATCH by patch() etc.
	 */
	protected function obtainData()
	{
		parent::obtainData();

		$method = strtolower( request()->method() );

		$this->data = $this->{$method}();
	}

	/**
	 * There maybe request with or without transaction ID (which is passed as the route param id).
	 * In case if id provided, return the purchase details.
	 * Otherwise return a list of pending transactions.
	 *
	 * @return array
	 */
	private function get()
	{
		if ( empty( (int) $this->arguments[0] ) )
			return Transaction::pendingPayments();

		$purchase = Purchase::where('transactionID', $this->arguments[0] )->first();

		return ( $purchase )
			? array_merge( $purchase->balance, [ 'coupon_code' => $purchase->coupon_code, 'giftcard_code' => $purchase->giftcard_code ] )
			: [];
	}

	/**
	 * Set transaction Denied
	 */
	private function delete()
	{
		Validator::make(
			array_merge( request()->all(), $this->arguments ),
			[
				'0' => 'required|integer|exists:transactions,transactionID,paymentStatus,' . Transaction::PENDING,
				'comment' => 'nullable|string|max:512'
			],
			[],
			[ 0 => 'Transaction ID']
		)->validate();

		$transaction = Transaction::find( $this->arguments[0] );

		Payment::gateway( PaymentGateway::GW_MANUAL )
			->withTransaction( $transaction )
			->cancel( request( 'comment' ) );
	}

	/**
	 * Set transaction Completed
	 */
	private function patch()
	{
		Validator::make(
			request()->all(),
			[
				'transaction_id' => 'required|integer|exists:transactions,transactionID,paymentStatus,' . Transaction::PENDING,
				'comment'        => 'nullable|string|max:191'
			]
		)->validate();


		$transaction = Transaction::find( request( 'transaction_id' ) );

		Payment::gateway( PaymentGateway::GW_MANUAL )
		       ->withData([
			       'transaction' => $transaction,
			       'description' => request( 'comment' )
		       ])
		       ->confirm();
	}

	/**
	 * Just an alias for patch method.
	 */
	private function put()
	{
		$this->patch();
	}

	/**
	 * Create new transaction.
	 * (Just a stub for possible further development of the page functionality)
	 */
	private function post()
	{
		abort( 404 );
	}
}
