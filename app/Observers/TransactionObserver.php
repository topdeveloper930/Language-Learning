<?php


namespace App\Observers;


use App\Student;
use App\Transaction;

class TransactionObserver {
	
	/**
	 * Handle to the Transaction "created" event.
	 *
	 * @param  Transaction $transaction
	 * @return void
	 */
	public function created( Transaction $transaction )
	{
		$this->activateStudent( $transaction );
	}

	/**
	 * Handle the Transaction "updated" event.
	 *
	 * @param  Transaction $transaction
	 * @return void
	 */
	public function updated( Transaction $transaction )
	{
		$this->activateStudent( $transaction );
	}

	private function activateStudent( $transaction )
	{
		if( Transaction::COMPLETED == strtolower( $transaction->paymentStatus ) ) {
			$transaction->student->active = Student::STUDENT_ACTIVE;
			$transaction->student->save();
		}
	}
}