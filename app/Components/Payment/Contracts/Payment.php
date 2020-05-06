<?php


namespace App\Components\Payment\Contracts;


use App\Transaction;

interface Payment {

	/**
	 * @return array of the calculated balance data (cost, total, discount, referral_credits, giftcard)
	 */
	public function balance();

	/**
	 * Sends the payment to the provider and creates "Pending" transaction instance
	 * (and some other, if need be - like gift card usage or referral credits...).
	 *
	 * @return array The response array contains transactionID field and, maybe, some other data.
	 */
	public function send();


	/**
	 * Check whether the payment was successful and set relevant models "Completed".
	 * Create necessary records.
	 *
	 * @return mixed
	 */
	public function confirm();

	/**
	 * Processes cancellation of the transaction.
	 *
	 * @param string|null $reason
	 *
	 * @return mixed
	 */
	public function cancel( $reason = null );

	public function webhook();

	/**
	 * Returns new clone of the driver with imported data.
	 *
	 * @param array $data
	 *
	 * @return Payment
	 */
	public function withData( $data );

	/**
	 * Returns new clone of the driver with imported Transaction.
	 *
	 * @param Transaction $transaction
	 *
	 * @return Payment
	 */
	public function withTransaction( Transaction $transaction );
}