<?php

namespace App\Notifications;

use App\Custom\Helper;
use App\Student;
use App\Teacher;
use App\Transaction;
use App\User;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentConfirmation extends Payment
{
	private $teachers;

	public function __construct( Transaction $transaction, $teachers = [] )
	{
		parent::__construct( $transaction );

		$this->teachers = $teachers;
	}

    /**
     * Get the mail representation of the notification.
     * There may be 3 types of notification email, depending on the $notifiable:
     *      Student
     *      Teacher
     *      Class Coordinator (Admin)
     *
     * @param  Student|Teacher|User $notifiable
     * @return MailMessage
     */
    public function toMail( $notifiable )
    {
    	switch ( get_class( $notifiable ) ) {
		    case \App\Student::class:
		    	return $this->toStudent( $notifiable );
		    case \App\Teacher::class:
			    return $this->toTeacher( $notifiable );
		    default:
			    return $this->toCoordinator( $notifiable );
	    }
    }

	private function toStudent( Student $student )
	{
		return ( new MailMessage )
			->markdown( 'notifications::email_ll' )
			->subject( __( 'emails.subject.student.payment_success' ) )
			->greeting( Helper::getGreeting( $student->accost(), $this->getLanguage(), $student->timezone_code() ) )
			->line( __('emails.payment.to_student_success', [
				'amount'   => number_format( $this->transaction->paymentAmount, 2 ),
				'hours'    => trans_choice( 'emails.payment.hours', $this->transaction->hours ),
				'language' => $this->getLanguage(),
				'course'   => $this->getCourseType(),
				'tutors'   => $this->getTeachersNames()
			]))
			->line( __('emails.payment.hours_added', ['url' => route( 'students', ['controller' => 'schedule-class'] )] ))
			->line( __('emails.payment.for_assist' ))
			->level( 'success' )
			->salutation( __( 'greeting.good_day', [], Helper::getLanguageCode( $this->getLanguage() ) ))
			->action( __('emails.payment.login'), route('login', ['role' => 'students']) )
			->with(__( 'emails.payment.to_cancel', ['terms_url' => route('page', ['controller' => 'terms-conditions'])]));
	}

	private function toTeacher( Teacher $teacher )
	{
		$student_accost = $this->transaction->student->accost();

		return ( new MailMessage )
			->markdown( 'notifications::email_ll' )
			->subject( __( 'emails.subject.teacher.payment_success', [
				'student' => $student_accost,
				'hours'   => trans_choice( 'emails.payment.hours', $this->transaction->hours ),
				'lessons' => $this->getCourseTitle()
			]))
			->greeting( Helper::getGreeting( $teacher->getFirstName(), '', $teacher->timezone_code() ) )
			->salutation( __( 'greeting.good_day' ) )
			->line( __('emails.payment.teacher_info', [
				'student'  => $student_accost,
				'hours'    => trans_choice( 'emails.payment.hours', $this->transaction->hours ),
				'language' => $this->getLanguage(),
				'course'   => $this->getCourseType()
			]))
			->line( __('emails.payment.teacher_success.0' ))
			->line( __('emails.payment.teacher_success.1' ))
			->line( __('emails.payment.teacher_success.2' ))
			->level( 'success' )
			->action( __('emails.payment.login'), route('login', ['role' => 'teachers']) );
	}

	private function toCoordinator( $coordinator )
	{
		$student_accost = $this->transaction->student->accost();

		return ( new MailMessage )
			->markdown( 'notifications::email_ll' )
			->subject( __( 'emails.subject.admin.payment_success', [ 'student' => $student_accost ] ) )
			->line( __('emails.payment.success_coordinator', [
				'accost'   => $student_accost,
				'amount'   => number_format( $this->transaction->paymentAmount, 2 ),
				'hours'    => trans_choice( 'emails.payment.hours', $this->transaction->hours ),
				'language' => $this->getLanguage(),
				'course'   => $this->getCourseType(),
				'tutors'    => $this->getTeachersNames()
			]))
			->line( __('emails.payment.no_action_required' ));
	}

	private function getTeachersNames()
	{
		$cnt = count( $this->teachers );
		$teachers = str_plural( 'teacher', $cnt ) . ' ';

		foreach ( $this->teachers as $teacher )
			$teachers .= $teacher->fullName() . ', ';

		return preg_replace('/,([^,]*)$/', ' and \1', rtrim( $teachers, ', ' ));
	}
}