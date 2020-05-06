<?php


namespace App\Observers;

use App\Referral;
use App\ReferralCredit;

class ReferralCreditObserver {
	
	/**
	 * Handle the ReferralCredit "created" event.
	 * Set referral realized, if this is a debit record.
	 *
	 * @param  ReferralCredit $referralCredit
	 * @return void
	 */
	public function created(ReferralCredit $referralCredit)
	{
		if( $referralCredit->amount >= 0 AND $referralCredit->transaction ) {
			if( !$referralCredit->transaction->referral )
				Referral::create([
					'id'        => $referralCredit->transaction->userID,
					'coupon_id' => $referralCredit->coupon_id,
					'realized'  => 1
				]);
			else
				$referralCredit->transaction->referral->update([ 'realized' => 1 ]);
		}
	}

	/**
	 * Handle the ReferralCredit "deleted" event.
	 * Referral bonus paid only once, hence, if a non negative credit entry deleted, set the referral "realized" to 0.
	 *
	 * @param  ReferralCredit $referralCredit
	 * @return void
	 */
	public function deleted( ReferralCredit $referralCredit )
	{
		if( $referralCredit->amount >= 0
		    AND $referralCredit->transaction
		        AND $referralCredit->transaction->referral
		            AND $referralCredit->transaction->referral->realized ) {
			$referralCredit->transaction->referral->update([ 'realized' => 0 ]);
		}
	}
}