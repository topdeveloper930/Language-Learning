<?php

namespace App\Notifications;

use App\Custom\Helper;
use App\Evaluation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EvaluationCreated extends Notification implements ShouldQueue
{
    use Queueable;

	/**
	 * @var Evaluation
	 */
    public $evaluation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Evaluation $evaluation)
    {
        $this->evaluation = $evaluation;
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
		    ->subject( __( 'emails.subject.student.progress_report') )
		    ->greeting( Helper::getGreeting( $notifiable->accost(), $this->evaluation->language, $notifiable->timezone_code() ) )
		    ->line( __('emails.progress_report.report_completed', ['language' => $this->evaluation->language, 'teacher' => $this->evaluation->teacher->fullName(), 'link' => route('page', ['controller' => 'language-levels'])]) )
		    ->action( __( 'emails.progress_report.action'), route( 'students', ['controller' => 'progress-report', 'id' => $this->evaluation->evaluationID] ) );
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
