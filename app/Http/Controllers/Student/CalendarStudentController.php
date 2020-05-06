<?php


namespace App\Http\Controllers\Student;


use App\Event;
use App\Http\Controllers\StudentController;
use Carbon\Carbon;

/**
 * Uses "legacy" template.
 * TODO: redo with new design from Zedalabs
 *
 * Class CalendarStudentController
 * @package App\Http\Controllers\Student
 */
class CalendarStudentController extends StudentController {

	protected $current_menu = 'pages.calendar.calendar';
	protected $page_title = 'pages.calendar.your_class_calendar';

	protected $js = [
		'google_api:client', 'student-calendar'
	];

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 * @deprecated Laravel 5.4 uses Carbon 1.20, which is not that handy with localization.
	 *             All the more, legacy site uses English only. Hence, format date should be localized after Laravel upgrade.
	 */
	protected function make()
	{
		$this->tplConfig->addCSS( 'js-calendar' );

		parent::make();

		/**
		 * The teacher assign functionality does not take into account numStudents.
		 * Therefore neither it is reckoned in here (Student->getCoursesLimited()).
		 * The student has to select correct course from the dropdown list.
		 */
		$this->setParams([
			'teachers' => $this->getTeachers(),
			'next_class' => $this->getNextClass(),
			'courses' => $this->user->getCoursesLimited(),
			'purchase_link' => sprintf(
				'<a href="%s"><b>%s</b></a>',
				url( '/students/purchase-lessons.php' ),
				__( 'pages.here' )
			)
		]);
	}

	private function getTeachers()
	{
		return $this->user->teachers()
		                  ->distinct()
		                  ->where( 'activeTeacher', 'Active' )
		                  ->orderBy( 'firstName' )
		                  ->get();
	}

	private function getNextClass()
	{
		$nextClass = $this->user->classes()
                                ->where( 'active', Event::ACTIVE )
                                ->where( 'eventStart', '>', gmdate( 'Y-m-d H:i:s' ) )
                                ->orderBy( 'eventStart' )->first();

		if( $nextClass ) {

			$dt = Carbon::createFromFormat( 'Y-m-d H:i:s', $nextClass->eventStart, 'GMT' );
			$dt->timezone($this->user->timezone_code() );

			$nextClass = $dt->format( 'l, F jS, \a\t g:i a' );
		}

		return $nextClass;
	}
}