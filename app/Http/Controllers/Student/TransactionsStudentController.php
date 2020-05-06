<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\StudentController;
use App\Transaction;

class TransactionsStudentController extends StudentController
{
	protected $js = [ 'jquery3_4', 'dataTables', 'student_transactions' ];

    protected $translation = 'student_transactions.js';

	protected function obtainData()
	{
		parent::obtainData();

		$this->data = $this->user->transactions()->where('paymentStatus', Transaction::COMPLETED)->get();
	}
}
