<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailResetPasswordToken extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;
    public $area;
    public $role;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $token, $area )
    {
        $this->token = $token;
        $this->area = $area;
        $this->role = str_singular( $this->area );
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
    public function toMail( $notifiable )
    {
        return (new MailMessage)
	        ->markdown( 'notifications::email_ll' )
	        ->level( 'warning' )
	        ->subject( __( 'emails.subject.forgot', [ 'role' => __( "pages.{$this->role}.{$this->role}" ) ] ) )
	        ->greeting( __( 'auth.greeting', [ 'accost' => $this->accost( $notifiable ) ] ) )
	        ->line( __( 'auth.reset_pass_text' ) )
	        ->action(__( 'auth.reset_action' ), route('password.reset', [ 'role' => $this->area, 'token' => $this->token, 'email' => urlencode( $notifiable->getEmailForPasswordReset() ) ] ))
	        ->line( __( 'auth.if_wrong' ) );
    }

    private function accost( $notifiable )
    {
    	if( 'students' == $this->area )
    		return $notifiable->fullName();

	    return ( 'admin' == $this->area )
		    ? $notifiable->member->fullName()
		    : $notifiable->{$this->role}->fullName();
    }
}
