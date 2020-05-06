<?php

namespace App\Notifications;

use App\Custom\Helper;
use App\Student;
use App\User;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentNoTeacher extends Payment
{
    /**
     * Get the mail representation of the notification.
     * There may be 3 types of notification email, depending on the $notifiable:
     *      Student
     *      Class Coordinator (Admin)
     *
     * @param  Student|User  $notifiable
     * @return MailMessage
     */
    public function toMail( $notifiable )
    {
    	return ( 'student' == $notifiable->getType() )
		    ? $this->toStudent( $notifiable )
		    : $this->toCoordinator( $notifiable );
    }

	/**
	 * @param Student $student
	 *
	 * @return MailMessage
	 */
    private function toStudent( $student )
    {
	    return ( new MailMessage )
		    ->markdown( 'notifications::email_ll', [ 'actionText' => __('emails.payment.login'), 'actionUrl' => route('login', ['role' => 'students']) ] )
		    ->subject( __( 'emails.subject.student.no_teacher' ) )
		    ->greeting( Helper::getGreeting( $student->accost(), $this->getLanguage(), $student->timezone_code() ) )
		    ->line( __('emails.payment.to_student_no_teacher_thankyou', [
			    'amount'   => $this->transaction->paymentAmount,
			    'hours'    => trans_choice( 'emails.payment.hours', $this->transaction->hours ),
			    'language' => $this->getLanguage(),
			    'course'   => $this->getCourseType()
		    ]))
		    ->line( __('emails.payment.to_student_no_teacher.0' ))
		    ->line( __('emails.payment.to_student_no_teacher.1' ))
		    ->line( __('emails.payment.to_student_no_teacher.2' ))
		    ->line( __('emails.payment.to_student_no_teacher.3' ))
		    ->salutation( __( 'greeting.good_day', [], Helper::getLanguageCode( $this->getLanguage() ) ));
    }

	/**
	 * @param User $coordinator
	 *
	 * @return MailMessage
	 */
	private function toCoordinator( $coordinator )
	{
		$student_accost = $this->transaction->student->accost();

		return ( new MailMessage )
			->markdown( 'notifications::email_ll', [ 'actionText' => __('emails.payment.login'), 'actionUrl' => route('login', ['role' => 'admin']) ] )
			->subject( __( 'emails.subject.admin.no_teacher', [ 'student' => $student_accost ] ) )
			->line( __('emails.payment.no_teacher') )
			->line( __('emails.payment.to_coordinator', [
				'accost'   => $student_accost,
				'email'    => $this->transaction->student->email,
				'amount'   => $this->transaction->paymentAmount,
				'hours'    => trans_choice( 'emails.payment.hours', $this->transaction->hours ),
				'language' => $this->getLanguage(),
				'course'   => $this->getCourseType()
			]));
	}
}