<?php

namespace App\Http\Controllers\Student;

use App\Evaluation;
use App\EvaluationLevel;
use App\Http\Controllers\StudentController;

class ProgressReportStudentController extends StudentController
{
	protected $translation = 'progress_report.js';

	protected $js = ['progress_report'];

	protected function make()
	{
		$this->setParam(
			'report',
			Evaluation::findOrFail( (int)$this->arguments[0] )
		);
	}

	protected function obtainData()
	{
		parent::obtainData();

		$this->data = EvaluationLevel::findOrFail( request('level' ) );
	}
}
