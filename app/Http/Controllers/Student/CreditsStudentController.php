<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\StudentController;
use App\Transaction;

class CreditsStudentController extends StudentController
{
	protected $js = [ 'jquery3_4', 'dataTables', 'credits' ];

	protected $translation = 'credits.js';

	protected function obtainData()
	{
		parent::obtainData();

		$this->data = $this->user->transactions()->where('paymentStatus', Transaction::COMPLETED)->get();
	}
}
