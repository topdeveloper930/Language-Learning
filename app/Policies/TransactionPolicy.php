<?php

namespace App\Policies;

use App\Student;
use App\Transaction;
use Illuminate\Contracts\Auth\Authenticatable;

class TransactionPolicy extends BasePolicy
{

	/**
	 * Determine whether the user can view the transaction.
	 *
	 * @param  Authenticatable  $user
	 * @param  \App\Transaction $transaction
	 * @return mixed
	 */
	public function view(Authenticatable $user, Transaction $transaction)
	{
		return $user instanceof Student AND $user->getPrimaryKey() == $transaction->userID;
	}

	/**
	 * Determine whether the user can create transaction.
	 *
	 * @param  Authenticatable  $user
	 * @return mixed
	 */
	public function create(Authenticatable $user, Transaction $transaction)
	{
		return $user instanceof Student;
	}

	/**
	 * Determine whether the user can update the event.
	 *
	 * @param  Authenticatable  $user
	 * @param  Transaction $transaction
	 * @return mixed
	 */
	public function update(Authenticatable $user, Transaction $transaction)
	{
		return false;
	}

	/**
	 * Determine whether the user can delete the transaction.
	 *
	 * @param  Authenticatable  $user
	 * @param  Transaction $transaction
	 * @return mixed
	 */
	public function delete(Authenticatable $user, Transaction $transaction)
	{
		return false;
	}
}
