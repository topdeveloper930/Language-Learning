<?php

namespace App\Notifications;

use App\Teacher;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Since the notification is goingn to be triggered from queue, we need not it to be ShouldQueue
 * Class SetTeacherGender
 * @package App\Notifications
 */
class TeacherGender extends Notification
{
    use Queueable;

    public $teacher;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( Teacher $teacher )
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
        return ( new MailMessage )
	                ->markdown( 'notifications::email_ll' )
	                ->subject( config( 'mail.subjects.coordinator.action_required' ) )
                    ->line( sprintf( 'This is to confirm new teacher %s record created.', $this->teacher->accost() ) )
                    ->line( 'However the system cannot automatically detect the teacher\'s gender and needs you to manually set it.' )
                    ->action('Edit Teacher\'s Profile', url( '/admin/edit-teacher.php?tid=' . $this->teacher->teacherID ) );
    }
}
