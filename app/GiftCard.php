<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
{
	const CREATED_AT = 'createDate';
	const UPDATED_AT = null;

	protected $table = 'giftCards';
	protected $primaryKey = 'giftCardID';

	/**
	 * code field used here for sake of future dropping redundant giftCardLog.giftCardsID field
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	protected function logs()
	{
		return $this->hasMany( GiftCardLog::class, 'code', 'code');
	}

	public function apply( $amount )
	{
		return ($amount <= $this->amountRemaining )
			? 0
			: $amount - $this->amountRemaining;
	}
}
