<?php

namespace App\Notifications;


use Illuminate\Notifications\Messages\MailMessage;

class ClassCancelled extends Lesson
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

    	if ( 'student' == $role  ) {
    		$role = 'student';
    		$student = $notifiable;
    		$teacher = $this->event->teacher;
    		$date = $student->formatUTCtoMyTimeZone( $this->event->eventStart, 'D, M jS \a\t g:i A' );
	    }
    	else {
		    $student = $this->event->student;
		    $teacher = $notifiable->getInstance();
		    $date = $teacher->formatUTCtoMyTimeZone( $this->event->eventStart, 'D, M jS \a\t g:i A' );
		    $translate_path = 'emails.class_cancelled.teacher.';
		    $translate_path .= ( 'student' === $this->_by ) ? 'student' : 'admin';
	    }

	    $line1 = ( 'student' === $role )
		    ? __( 'emails.class_cancelled.student', [ 'teacher' => $teacher->accost() . ' (' . $teacher->email . ')', 'dt' => $student->formatUTCtoMyTimeZone( $this->event->eventStart, 'l, F jS \a\t g:i A' ) ] )
		    : __( $translate_path, [ 'student' => $student->accost() . ' (' . $student->email . ')', 'dt' => $teacher->formatUTCtoMyTimeZone( $this->event->eventStart, 'l, F jS \a\t g:i A' ) ] );

        return ( new MailMessage )
	        ->markdown( 'notifications::email_ll' )
	        ->subject( __( 'emails.subject.' . $role . '.class_cancelled', [ 'date' => $date, 'student' => $student->accost() ] ) )
	        ->line( $line1 )
	        ->line( __( 'emails.class_cancelled_remark' ) );
    }
}
