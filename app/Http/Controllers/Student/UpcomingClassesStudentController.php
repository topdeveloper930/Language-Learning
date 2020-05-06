<?php


namespace App\Http\Controllers\Student;


use App\Http\Controllers\StudentController;

/**
 * Uses "legacy" template.
 * TODO: redo with new design from Zedalabs
 *
 * Class UpcomingClassesStudentController
 * @package App\Http\Controllers\Student
 */
class UpcomingClassesStudentController extends StudentController {

	protected $template = 'student.upcoming-classes';

	protected $current_menu = 'pages.calendar.calendar';
	protected $page_title = 'pages.calendar.your_class_calendar';

	protected $js = [ 'google_api:client', 'upcoming-classes' ];

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function make()
	{
		$this->tplConfig->addCSS( 'js-calendar' );

		parent::make();

		$this->setParams([
			'upcoming_classes' => $this->user->classes()->with('teacher')
                 ->where('active', '=', 1)
                 ->where('eventStart', '>', gmdate( 'Y-m-d H:i:s' ))
                 ->orderBy('eventStart')
                 ->get()
		]);
	}
}