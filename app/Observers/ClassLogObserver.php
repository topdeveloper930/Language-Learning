<?php


namespace App\Observers;


use App\ClassLog;
use App\Notifications\ClassLogged;
use App\Notifications\LowOnClasses;
use App\Notifications\NoHours;
use App\Student;

class ClassLogObserver {
	
	/**
	 * Handle ClassLog "created" event.
	 *
	 * @param  ClassLog $classLog
	 * @return void
	 */
	public function created(ClassLog $classLog)
	{
		if( Student::CLASS_LOG_MESSAGES_ACTIVE == $classLog->student->classLogMessages )
			$classLog->student->notify( new ClassLogged( $classLog ) );

		$language = substr( $classLog->course, 0, strpos( $classLog->course, '-' ));

		$remainder = $classLog->student->getCourseRemainingBalance(
			substr( $classLog->course, strpos( $classLog->course, '-' ) + 1),
			$classLog->numStudents
		);

		if( $remainder <= 2 AND $remainder > 1 )
			$classLog->student->notify( new LowOnClasses( $language, $classLog->teacher->fullName() ) );
		elseif ( $remainder <= 0 )
			$classLog->student->notify( new NoHours( $language, $classLog->teacher->fullName() ) );
	}
}