<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\TeacherController;
use App\Notifications\TrialClassNoShow;
use App\TrialClass2Teacher;
use Illuminate\Support\Facades\View;

class DashboardTeacherController extends TeacherController
{
	protected $js = [ 'jquery3_4', 'dataTables', 'teacher_dashboard' ];

	protected $translation = 'teacher_dashboard.js';

	protected function obtainData()
	{
		parent::obtainData();

		abort_unless(
			isset( $this->arguments[0] ) AND method_exists( $this, $this->arguments[0] ),
			404
		);

		$this->data = $this->{$this->arguments[0]}();
	}

	private function upcoming()
	{
		return $this->teacher->getUpcomingEvents();
	}

	private function trials()
	{
		return $this->teacher->listTrialClassesToLog();
	}

	private function students()
	{
		return $this->teacher->getStudentsWithCredits();
	}

	private function student()
	{
		$student = $this->teacher->students()
		                         ->having( 'studentID', (int) request('id') )
		                         ->firstOrFail();

		return $this->details( $student );
	}

	private function trial()
	{
		$tc2t = TrialClass2Teacher::where([
			['teacherID', $this->teacher->getPrimaryKey()],
			['trialClass2Teachers', (int) request('id')],
			['results', '0']
		])->firstOrFail();

		return $this->details( $tc2t->trialClassLog, date('l - M jS - g:i A', strtotime($tc2t->studentClassDate)) );
	}

	private function details( $student, $studentTime = null )
	{
		return [
			'html' => View::make('layout.zedalabs.widgets.student_details_modal', [
				'student'     => $student,
				'studentTime' => $studentTime
			])->render()
		];
	}

	private function trial_noshow()
	{
		abort_if('post' !== strtolower(request()->getMethod()), 422 );

		$trial2Teacher = $this->teacher->trials2Teacher()->where([
			[ 'trialClass2Teachers', (int) request('id') ],
			['results', 0]
		])->firstOrFail();

		$trial2Teacher->results = TrialClass2Teacher::NOSHOW;

		$trial2Teacher->trialClassLog->notify( new TrialClassNoShow( $this->teacher ) );

		return $trial2Teacher->save();
	}

	private function trial_completed()
	{
		//TODO
	}
}
