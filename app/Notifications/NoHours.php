<?php

namespace App\Notifications;

use App\Custom\Helper;
use Illuminate\Notifications\Messages\MailMessage;

class NoHours extends LowOnClasses
{
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
	    return ( new MailMessage )
		    ->markdown( 'notifications::email_coordinator' )
		    ->level('success')
		    ->subject( __( 'emails.subject.student.no_hours' ) )
		    ->greeting( Helper::getGreeting( $notifiable->accost(), $this->language, $notifiable->timezone_code() ) )
		    ->line( __( 'emails.no_hours', ['language' => $this->language, 'teacher' => $this->teacher] ) )
		    ->action( __( 'emails.low_on_classes.purchase_more' ), route( 'students', ['controller' => 'purchase', 'id' => strtolower($this->language)] ) )
		    ->salutation( __( 'greeting.good_day', [], Helper::getLanguageCode( $this->language ) ));
    }

}
