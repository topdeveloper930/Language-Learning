<?php


namespace App\Http\Controllers\Teacher;


use App\CourseList;
use App\EvaluationLevel;
use App\Http\Controllers\TeacherController;
use App\Student;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentEvaluationTeacherController extends TeacherController {

	protected $js = ['teacher_student_evaluation'];

	protected $translation = 'student_evaluation.js';

	protected function before()
	{
		parent::before();

		abort_unless((int) request()->route('id'), 404);

		if(!strcasecmp('post', request()->method())) { // Is POST

			$data = $this->validateInput();

			$course = CourseList::where('courseType', $data['course'])->first();

			$data['language'] = $course->language;

			$student = Student::find( $data['studentID'] );

			$data['classesTaken'] = $student->classLogs()
			                                ->where( [
				                                [ 'course', $course->getCourseTitle() ],
				                                [ 'active', 1 ]
			                                ] )->sum( 'creditsUsed' );

			if( $evaluation = $this->saveEvaluation( $data ) ) {

				$this->notification(__('student_evaluation.saved'), 'success');

				$this->redirectResponse = redirect(route('teachers', ['controller' => 'dashboard']));

			}
			else {
				$this->notification(__('student_evaluation.not_saved'), 'caution');
			}
		}
	}

	protected function make()
	{
		parent::make();

		$student = $this->teacher->students()
		                         ->wherePivot('active', 1)
		                         ->wherePivot( 'studentID', (int) $this->arguments[0] )
		                         ->firstOrFail();

		$this->setParams([
			'student'     => $student,
			'courseType'  => request( 'course' ),
			'assignments' => $this->teacher->assignments()
			                               ->select('language', 'course')
			                               ->where( [
				                               ['active', 1],
				                               ['studentID', $this->arguments[0]]
			                               ] )->distinct()->get(),
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

		$studentID = request()->route('id');

		$validator = Validator::make(
			array_merge(['studentID' => $studentID], request()->all()),
			[
				'course'         => 'required|string|exists:teacher2student,course,active,1,studentID,' . $studentID . ',teacherID,' . $this->user->getInstance()->getPK(),
				'comments'       => 'required|string|max:5000',
				'speakingLevel'  => $levelRules,
				'listeningLevel' => $levelRules,
				'readingLevel'   => $levelRules,
				'writingLevel'   => $levelRules
			],
			['course.exists' => __('student_evaluation.course_not_assigned')]
		);
		$validator->validate();

		return $validator->valid();
	}

	private function saveEvaluation( $data )
	{
		$speakingLevel = EvaluationLevel::where([
			['skill', 'Speaking'],
			['title', $data['speakingLevel']]
		])->first();

		$listeningLevel = EvaluationLevel::where([
			['skill', 'Listening'],
			['title', $data['listeningLevel']]
		])->first();

		$writingLevel = EvaluationLevel::where([
			['skill', 'Writing'],
			['title', $data['writingLevel']]
		])->first();

		$readingLevel = EvaluationLevel::where([
			['skill', 'Reading'],
			['title', $data['readingLevel']]
		])->first();

		$data['speakingLevelTitle']  = $data['speakingLevel'];
		$data['listeningLevelTitle'] = $data['listeningLevel'];
		$data['writingLevelTitle']   = $data['writingLevel'];
		$data['readingLevelTitle']   = $data['readingLevel'];

		$data['speakingLevel']    = ( $speakingLevel ) ? $speakingLevel->level : null;
		$data['speakingLevelID']  = ( $speakingLevel ) ? $speakingLevel->evaluationLevelsID : null;
		$data['listeningLevel']   = ( $listeningLevel ) ? $listeningLevel->level : null;
		$data['listeningLevelID'] = ( $listeningLevel ) ? $listeningLevel->evaluationLevelsID : null;
		$data['writingLevel']     = ( $writingLevel ) ? $writingLevel->level : null;
		$data['writingLevelID']   = ( $writingLevel ) ? $writingLevel->evaluationLevelsID : null;
		$data['readingLevel']     = ( $readingLevel ) ? $readingLevel->level : null;
		$data['readingLevelID']   = ( $readingLevel ) ? $readingLevel->evaluationLevelsID : null;

		return $this->user->getInstance()->evaluations()->create( $data );
	}

}