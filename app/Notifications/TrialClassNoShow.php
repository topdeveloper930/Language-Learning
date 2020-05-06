<?php

namespace App\Notifications;

use App\Custom\Helper;
use App\Teacher;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TrialClassNoShow extends Notification implements ShouldQueue
{
    use Queueable;

	/**
	 * @var Teacher
	 */
    public $teacher;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( Teacher $teacher = null )
    {
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
    	if( !$this->teacher )
    		$this->teacher = $notifiable->getAssignedTeacher();

        return (new MailMessage)
	        ->markdown( 'notifications::email_coordinator' )
	        ->subject(__('emails.trial_missed'))
            ->greeting(Helper::getGreeting(
            	$notifiable->sex . ' ' . $notifiable->lastName,
	            $notifiable->language,
	            $notifiable->timezone_code()
            ))
            ->line(__('emails.trial_no_show', ['teacher' => $this->teacher->accost()]))
	        ->salutation( __( 'greeting.good_day', [], Helper::getLanguageCode( $notifiable->language ) ));
    }
}
