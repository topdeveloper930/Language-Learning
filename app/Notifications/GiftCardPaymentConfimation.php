<?php

namespace App\Notifications;

use App\Custom\Helper;
use App\Student;
use App\Teacher;
use App\Transaction;
use App\User;
use Illuminate\Notifications\Messages\MailMessage;

class GiftCardPaymentConfirmation extends Payment
{
    private $student;

    private $recipient = [];

	public function __construct( Transaction $transaction, Student $student, $receipient = [])
	{
		parent::__construct( $transaction );

        $this->student    = $student;
        $this->receipient = $receipient;
	}

    public function toMail( $notifiable )
    {
    	// switch ( get_class( $notifiable ) ) {
		//     case \App\Student::class:
		//     	return $this->toStudent( $notifiable );
		//     case \App\Teacher::class:
		// 	    return $this->toTeacher( $notifiable );
		//     default:
		// 	    return $this->toCoordinator( $notifiable );
	    // }
    }

    public function toBuyer( $notificable )
    {
        
    }

    public function toReceiver( $notificable )
    {
        $message = __('emails.gift_card.')
        return ( new MailMessage )
                    ->markdown( 'notifications::email_ll' )
                    ->subject( __('emails.subject.gift_card.receiver') )
                    ->line($student -> firstName . " " . $student -> lastName);
                    ->line($message);
    }

    public function toNewSignUp( $notificable )
    {
        return ( new MailMessage )
	                ->markdown( 'notifications::email_ll' )
                    ->subject( __('emails.subject.gift_card.new_signup') )
                    ->line();
    }

}