<?php

namespace App\Notifications;

use App\Custom\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LowOnClasses extends Notification implements ShouldQueue
{
    use Queueable;

    public $language;

    public $teacher;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $language, $teacher )
    {
        $this->language = $language;
        $this->teacher = $teacher;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

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
		    ->subject( __( 'emails.subject.student.low_on_classes' ) )
		    ->greeting( Helper::getGreeting( $notifiable->accost(), $this->language, $notifiable->timezone_code() ) )
		    ->line( __( 'emails.low_on_classes.enjoy_lessons', ['language' => $this->language, 'teacher' => $this->teacher] ) )
		    ->action( __( 'emails.low_on_classes.purchase_more' ), route( 'students', ['controller' => 'purchase', 'id' => strtolower($this->language)] ) )
		    ->salutation( __( 'greeting.good_day', [], Helper::getLanguageCode( $this->language ) ));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
