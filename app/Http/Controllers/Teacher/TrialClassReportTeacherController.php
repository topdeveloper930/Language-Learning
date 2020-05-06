<?php

namespace App\Http\Controllers\Teacher;

use App\EvaluationLevel;
use App\Http\Controllers\TeacherController;
use App\Jobs\TrialClassCompleted;
use App\TrialClass2Teacher;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TrialClassReportTeacherController extends TeacherController
{
	protected $js = ['teacher_trial_class_report'];

	protected $translation = 'teacher_trial_class_report.js';

	protected function before()
	{
		parent::before();

		if(!strcasecmp('post', request()->method())) { // Is POST

			$data = $this->validateInput();

			$tc2t = TrialClass2Teacher::find( $data['trialClass2Teachers'] );

			dispatch( new TrialClassCompleted( $tc2t->trialClassLog, $this->user->getInstance(), $data ) );

			$tc2t->results = TrialClass2Teacher::COMPLETED;
			$tc2t->save();

			$this->notification(__('teacher_trial_class_report.saved'), 'success');

			$this->redirectResponse = redirect(route('teachers', ['controller' => 'dashboard']));
		}
	}

	protected function make()
	{
		parent::make();

		$this->setParams([
			'trial' => TrialClass2Teacher::where( [
								[ 'trialClass2Teachers', (int) Arr::get( $this->arguments, 0 ) ],
								[ 'results', '0' ]
							] )
			                           ->firstOrFail(),
			'levels' => EvaluationLevel::select('level', 'title')
			                           ->groupBy(['level', 'title'])
			                           ->orderBy('level')
			                           ->get()
		]);
	}

	private function validateInput()
	{
		$levels = EvaluationLevel::select('level', 'title')
		                         ->groupBy(['level', 'title'])
		                         ->pluck('title')
		                         ->toArray();

		$levelRules = [
			'required',
			'string',
			Rule::in($levels)
		];

		$validator = Validator::make(
			array_merge(['trialClass2Teachers' => request()->route('id')], request()->all()),
			[
				'trialClass2Teachers'   => 'required|integer|exists:trialClass2Teachers',
				'comments'              => 'required|string|max:5000',
				'speakingLevel'         => $levelRules,
				'listeningLevel'        => $levelRules,
				'readingLevel'          => $levelRules,
				'writingLevel'          => $levelRules
			]
		);
		$validator->validate();

		return $validator->valid();
	}
}
