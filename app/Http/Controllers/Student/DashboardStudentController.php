<?php

namespace App\Http\Controllers\Student;

use App\ClassLog;
use App\Http\Controllers\StudentController;

class DashboardStudentController extends StudentController
{
	protected $translation = 'students_dashboard';

	protected $js = [
		'jquery3_4', 'dataTables', 'student_dashboard'
	];

	protected function make()
	{
		parent::make();

		$upcoming_classes = $this->user->classes()->with( 'teacher' )
                               ->where( 'active', '=', 1 )
                               ->whereRaw( "DATE_ADD(eventStart, INTERVAL ? MINUTE) > UTC_TIMESTAMP", [config('main.max_class_tolerance', 120)] )
                               ->orderBy( 'eventStart' )
                               ->get();

		$this->setParams([
			'upcoming_classes' => $upcoming_classes,
			'upcoming_total'   => count( $upcoming_classes ),
			'courseArr'        => $this->user->getCoursesLimited()
		]);
	}

	protected function obtainData()
	{
		parent::obtainData();

		$this->data = (new ClassLog)->studentCompletedClasses( $this->user->studentID );
	}
}
