<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\StudentController;

class ProgressReportsStudentController extends StudentController
{
	protected $js = [ 'jquery3_4', 'dataTables', 'progress_reports' ];

	protected $translation = 'progress_reports.js';

	protected function obtainData()
	{
		parent::obtainData();

		$this->data = $this->user->progressReports();
	}

}
