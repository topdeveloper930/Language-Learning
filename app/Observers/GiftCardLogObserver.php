<?php


namespace App\Observers;


use App\GiftCardLog;

class GiftCardLogObserver {
	
	/**
	 * Handle GiftCardLog "created" event.
	 *
	 * @param  GiftCardLog $logEntry
	 * @return void
	 */
	public function created(GiftCardLog $logEntry)
	{
		$logEntry->giftCard->amountRemaining -= $logEntry->amount;
		$logEntry->giftCard->save();

		// giftCardsID field is deprecated but might be still in use somewhere. Let's check its existence and fill it out.
		if( $logEntry->hasAttribute( 'giftCardsID' ) AND !$logEntry->giftCardsID )
			$logEntry->update([ 'giftCardsID' => $logEntry->giftCard->giftCardID ]);
	}

	/**
	 * Handle GiftCardLog "deleted" event.
	 *
	 * @param  GiftCardLog $logEntry
	 * @return void
	 */
	public function deleted(GiftCardLog $logEntry)
	{
		$logEntry->giftCard->amountRemaining += $logEntry->amount;
		$logEntry->giftCard->save();
	}
}