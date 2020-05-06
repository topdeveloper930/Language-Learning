<?php

namespace App;

use App\Traits\CheckAttribute;
use Illuminate\Database\Eloquent\Model;

class GiftCardLog extends Model
{
	use CheckAttribute;

	const CREATED_AT = 'createDate';
	const UPDATED_AT = null;

	protected $table = 'giftCardsLog';
	protected $primaryKey = 'giftCardsLogID';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'studentID', 'giftCardsID', 'code', 'amount', 'paidFor', 'transactionID'
	];

	/**
	 * code field used here for sake of future dropping redundant giftCardLog.giftCardsID field
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function giftCard()
	{
		return $this->belongsTo(GiftCard::class, 'code', 'code');
	}

	public function transaction()
	{
		return $this->belongsTo(Transaction::class, 'transactionID', 'transactionID');
	}
}
