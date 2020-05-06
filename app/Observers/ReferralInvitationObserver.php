<?php


namespace App\Observers;


use App\ReferralInvitation as Invitation;
use App\Mail\ReferralInvitation as InvitationMail;
use Illuminate\Support\Facades\Mail;

class ReferralInvitationObserver {
	
	/**
	 * Handle to the Event "created" event.
	 *
	 * @param  Invitation $invitation
	 *
	 * @return void
	 */
	public function created( Invitation $invitation )
	{
		Mail::to($invitation->email)
		    ->cc($invitation->student->email, $invitation->student->fullname())
		    ->queue(new InvitationMail( $invitation ));
	}
}