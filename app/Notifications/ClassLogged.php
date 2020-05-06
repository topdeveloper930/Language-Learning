<?php

namespace App\Notifications;

use App\ClassLog;
use App\Custom\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ClassLogged extends Notification implements ShouldQueue
{
    use Queueable;

	/**
	 * @var ClassLog
	 */
    public $classLog;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $classLog )
    {
        $this->classLog = $classLog;
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
    	$language = substr($this->classLog->course, 0, strpos($this->classLog->course, '-'));

    	$details = __(
    		'emails.class_logged.details',
		    [
			    'date'    => $this->classLog->classDate,
			    'minutes' => $this->classLog->creditsUsed * 60,
			    'course'  => $this->classLog->course
		    ]
	    );

        return (new MailMessage)->markdown( 'notifications::email_ll' )
                                ->subject( __( 'emails.subject.student.class_logged' ) )
                                ->greeting( Helper::getGreeting( $notifiable->accost(), $language, $notifiable->timezone_code() ) )
			                    ->line( __( 'emails.class_logged.automated_message', ['teacher' => $this->classLog->teacher->fullName()] ) )
			                    ->line( __( 'emails.class_logged.class_details', ['details' => $details] ) )
			                    ->line( __( 'emails.class_logged.class_notes' ) )
			                    ->line( __( 'emails.class_logged.notes', ['notes' => $this->classLog->whatWasStudied] ) )
			                    ->line( __( 'emails.class_logged.review_class', ['link' => route('students', ['controller' => 'disable-class-log-message'])] ) )
			                    ->action( __( 'emails.class_logged.see_class_logs'), route('students', ['controller' => 'dashboard']) )
			                    ->salutation( __( 'greeting.good_day', [], Helper::getLanguageCode( $language ) ));
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
