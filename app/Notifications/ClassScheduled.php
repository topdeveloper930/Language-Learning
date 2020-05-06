<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;

class ClassScheduled extends Lesson
{
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail( $notifiable )
    {
	    $role = $notifiable->getType();

    	if ( 'student' == $role ) {
    		$student = $notifiable->getInstance();
		    $teacher = $this->event->teacher;
    		$date = $student->formatUTCtoMyTimeZone( $this->event->eventStart, 'D, M jS \a\t g:i A' );
    		$line2 = __( 'emails.class_scheduled_remark.student' );
	    }
    	else {
		    $teacher = $notifiable->getInstance();
		    $student = $this->event->student;
		    $date = $teacher->formatUTCtoMyTimeZone( $this->event->eventStart, 'D, M jS \a\t g:i A' );
		    $translate_path = 'emails.class_scheduled.teacher.';
		    $translate_path .= ( 'student' === $this->_by ) ? 'student' : 'admin';
		    $line2 = __( 'emails.class_scheduled_remark.teacher' );
	    }

	    $line1 = ( 'student' === $role )
		    ? __( 'emails.class_scheduled.student', [ 'teacher' => $teacher->accost() . ' (' . $teacher->email . ')', 'dt' => $student->formatUTCtoMyTimeZone( $this->event->eventStart, 'l, F jS \a\t g:i A' ) ] )
		    : __( $translate_path, [ 'student' => $student->accost() . ' (' . $student->email . ')', 'dt' => $teacher->formatUTCtoMyTimeZone( $this->event->eventStart, 'l, F jS \a\t g:i A' ) ] );

        return ( new MailMessage )
	        ->markdown( 'notifications::email_ll' )
	        ->subject( __( 'emails.subject.' . $role . '.class_scheduled', [ 'date' => $date, 'student' => $student->accost() ] ) )
	        ->line( $line1 )
	        ->line( $line2 );
    }
}
