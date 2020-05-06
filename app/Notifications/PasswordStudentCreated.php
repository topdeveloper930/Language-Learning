<?php

namespace App\Notifications;

use App\Custom\Helper;
use App\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordStudentCreated extends Notification implements ShouldQueue
{
    use Queueable;

	/**
	 * @var string
	 */
    public $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $password )
    {
        $this->password = $password;
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
     * @param  Student  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
	        ->markdown( 'notifications::email_ll' )
	        ->subject(__('emails.your_ll_login'))
            ->greeting(__('emails.welcome'))
            ->line(Helper::getGreeting(
		        $notifiable->getTitle() . ' ' . $notifiable->lastName,
		        '',
		        $notifiable->timezone_code()
	        ))
	        ->line(__('emails.account_created'))
	        ->line(__('emails.here_info'))
	        ->line(__('emails.username', ['email' => $notifiable->getEmail()]))
	        ->line(__('emails.password', ['pass' => $this->password]))
	        ->action(__( 'emails.login_now'), route('login', [ 'role' => $notifiable->getArea()] ))
	        ->line(__( 'emails.any_questions'));
    }
}
