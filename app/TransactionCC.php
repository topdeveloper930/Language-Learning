<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionCC extends Model
{
	const CREATED_AT = 'createDate';
	const UPDATED_AT = null;

	protected $table = 'transactions_cc';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'transactions_ccID';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'studentID', 'status', 'lastFour', 'amount', 'description', 'error'
	];

	public function student()
	{
		return $this->belongsTo( Student::class, 'studentID', 'studentID' );
	}

	public function transaction()
	{
		return $this->belongsTo( Transaction::class, 'transactionID', 'transactionID' );
	}

}
