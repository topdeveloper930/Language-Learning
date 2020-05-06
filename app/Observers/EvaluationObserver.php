<?php


namespace App\Observers;


use App\Evaluation;
use App\Notifications\EvaluationCreated;

class EvaluationObserver {
	
	/**
	 * Handle to the Evaluation "created" event.
	 *
	 * @param  Evaluation  $evaluation
	 * @return void
	 */
	public function created(Evaluation $evaluation)
	{
		$evaluation->student->notify( new EvaluationCreated($evaluation) );
	}

}