<?php

namespace App\Notifications;

use App\Custom\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Since the notification is goingn to be triggered from queue, we need not it to be ShouldQueue
 * Class AffiliatesPageController
 * @package App\Notifications
 */
class Affiliate extends Notification implements ShouldQueue
{
    use Queueable;

    public $user_mail;
    public $user_name;
    public $note;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $user_name, $user_mail, $note = "" )
    {
        $this->user_name = $user_name;
        $this->user_mail = $user_mail;
        $this->note      = $note;
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
	                ->markdown( 'notifications::email_ll' )
                    ->subject( __('emails.subject.admin.affiliate_received') )
                    ->line( sprintf( 'User Name : %s  User Email : %s', $this -> user_name, $this -> user_mail))
                    ->line( 'Note : ')
                    ->line( $this -> note);
    }
}
