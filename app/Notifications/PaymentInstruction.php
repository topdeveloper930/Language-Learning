<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class PaymentInstruction extends Payment
{
    /**
     * Get the mail representation of the notification.
     * Assume only 2 methods that need instruction: Wire Transfer and U.S. Check.
     *
     * @param  \App\Student  $notifiable
     * @return MailMessage
     */
    public function toMail( $notifiable )
    {
	    $gateway = ( 'Bank Transfer' == $this->transaction->paymentGateway )
		    ? __( 'student_purchase.wire_transfer' )
		    : __( 'student_purchase.check_payment' );

    	return ( new MailMessage )
	        ->markdown(
	        	'notifications::payment_instruction',
		        [ 'transaction' => $this->transaction, 'gateway' => $gateway, 'paymentFor' => $this->getCourseTitle() ]
	        )
	        ->subject( __( 'student_purchase.instruction', [ 'gateway' => $gateway ] ) );
    }
}