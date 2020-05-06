<?php

namespace App\Http\Controllers\Student;

use App\CourseList;
use App\Event;
use App\Http\Controllers\StudentController;

class ScheduleClassStudentController extends StudentController
{
	protected $translation = 'schedule_class.js';

	protected $js = [ 'jquery3_4', 'jquery_ui', 'google_api:client' ];

	protected $class = null;

	public function __invoke()
	{
		$calendarID = request()->route()->parameter('id');

		if( $calendarID ) {
			$this->class = Event::findOrFail( $calendarID );

			if( !$this->user->can('update', $this->class) )
				throw new \Illuminate\Auth\Access\AuthorizationException('Restricted');

			if( $this->class->eventStart < gmdate( 'Y-m-d H:i:s', strtotime( config( 'main.cancel_from', 'tomorrow' )) ) )
				return redirect( route('students', ['controller' => 'dashboard']) )->withErrors(['eventStart' => trans('validation.event_after', [ 'hours' => config( 'main.cancel_advance', 24 ) ] )]);
		}

		$this->setParam('class', $this->class );

		return parent::__invoke();
	}

	protected function make()
	{
		parent::make();

		$this->tplConfig->addCSS( 'datepicker' );

		if('en' != app()->getLocale())
			$this->tplConfig->addJS([ '/public/js/lib/datepicker-lang/' . app()->getLocale() . '.js' ]);

		$this->tplConfig->addJS([ 'student_schedule_class' ]);

		/**
		 * The teacher assign functionality does not take into account numStudents.
		 * Therefore neither it is reckoned in here (Student->getCoursesLimited()).
		 * The student has to select correct course from the dropdown list.
		 */
		$this->setParams([
			'teachers' => $this->getTeachers(),
			'courses' => $this->user->getCoursesLimited()
		]);
	}

	protected function obtainData()
	{
		parent::obtainData();

		$this->data = CourseList::assignedTo( $this->user->getPK(), (integer) request()->get('teacherID'));
	}

	private function getTeachers()
	{
		return $this->user->teachers()
		                  ->distinct()
		                  ->where( 'activeTeacher', 'Active' )
		                  ->orderBy( 'firstName' )
		                  ->get();
	}
}
