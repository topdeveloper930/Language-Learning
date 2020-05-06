<?php

namespace App\Http\Controllers\Page;

use \App\Http\Controllers\PageController;
use App\LearningQuizResult;
use DB;
use Illuminate\Support\Arr;

class QuizResultPageController extends PageController {

	protected $js = ['quiz_result'];

    protected function make()
	{
        parent::make();

        $result = LearningQuizResult::findOrFail(Arr::get($this->arguments, 0));

        $styles = [
	        'Visual' => $result->visual,
	        'Auditory' => $result->auditory,
	        'Kinesthetic' => $result->kinesthetic
        ];

		arsort($styles);
		reset($styles);
		$first_key = key($styles);

		$this -> setParams([
			'shareContent'  => strtoupper($first_key . '-'. $result->style),
			'result'        => $result,
			'styles'         => $styles
		]);
    }
}
