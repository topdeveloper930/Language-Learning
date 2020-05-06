<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\ReferralInvitation as Invitation;
use App\Config;

class ReferralInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

	/**
	 * @var Invitation
	 */
    public $invitation;

	/**
	 * Create a new message instance.
	 *
	 * @param Invitation $invitation
	 */
    public function __construct( Invitation $invitation )
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    $refConfig = Config::overriddenValuesByName( 'referral_program' );

	    $discount = ( !empty( $refConfig['referral_discount_amount'] ) )
		    ? sprintf('$%s US', number_format( $refConfig['referral_discount_amount'], 2 ) )
		    : $refConfig['referral_discount_percent'] . '%';

        return $this->from( config('legacy.globals.mainEmail'), config('legacy.globals.businessName') )
                    ->markdown('vendor.emails.referral_invitation')
                    ->subject( __( 'emails.refer.subject', [ 'student' => $this->invitation->student->accost() ] ) )
	                ->with( 'discount', $discount );
    }
}
