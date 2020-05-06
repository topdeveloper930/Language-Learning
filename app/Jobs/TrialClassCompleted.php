<?php

namespace App\Jobs;

use App\EvaluationLevel;
use App\Notifications\PasswordStudentCreated;
use App\Student;
use App\Teacher;
use App\TrialClassLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Subscription;

class TrialClassCompleted implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * @var TrialClassLog
	 */
    private $trialClass;

	/**
	 * @var Teacher
	 */
    private $teacher;

	/**
	 * @var array
	 */
	protected $evaluationData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( TrialClassLog $trialClass, Teacher $teacher, $evaluationData )
    {
        $this->trialClass = $trialClass;
        $this->teacher = $teacher;
        $this->evaluationData = $evaluationData;
    }

    /**
     * Execute the job.
     * 1) Check if student exists and create, if not;
     * 2) Assign the teacher to the student, if not yet assigned
     * 3) Save evaluation
     * 4) Send notification to the student
     * 5) Check ActiveCampaign status. If student is not active yet, then set group TRIAL_COMPLETED
     *
     * @return void
     */
    public function handle()
    {
        $student = $this->createStudentIfNotExist();

        $this->attachStudent( $student->studentID );

        $this->saveEvaluation( $student->studentID );

        Subscription::driver()
                    ->setTrialCompletedIfNotActive( $student->getEmail() );
    }

	/**
	 * @return Student|\Illuminate\Database\Eloquent\Model|null
	 */
	private function createStudentIfNotExist()
	{
		$student = Student::where('email', $this->trialClass->email )->first();

		if(!$student) {
			$password = str_random( 8 );

			$student = $this->trialClass->createStudent($password);

			$student->notify( new PasswordStudentCreated($password) );
		}

		return $student;
	}

	private function attachStudent( $studentID ) {
		if ( ! DB::table( 'teacher2student' )
		         ->where( [
			         [ 'teacherID', $this->teacher->getKey() ],
			         [ 'studentID', $studentID ],
			         [ 'active', 1 ],
		         ] )
		         ->count() )
		{
			$this->teacher->students()->attach( $studentID, [
				'language' => $this->trialClass->language,
				'course'   => $this->trialClass->course
			] );
		}
	}

	/**
	 * @param int $studentID
	 */
	private function saveEvaluation( $studentID )
	{
		$speakingLevel = EvaluationLevel::where([
			['skill', 'Speaking'],
			['title', $this->evaluationData['speakingLevel']]
		])->first();

		$listeningLevel = EvaluationLevel::where([
			['skill', 'Listening'],
			['title', $this->evaluationData['listeningLevel']]
		])->first();

		$writingLevel = EvaluationLevel::where([
			['skill', 'Writing'],
			['title', $this->evaluationData['writingLevel']]
		])->first();

		$readingLevel = EvaluationLevel::where([
			['skill', 'Reading'],
			['title', $this->evaluationData['readingLevel']]
		])->first();

		$this->teacher->evaluations()->create([
			'studentID'           => $studentID,
			'language'            => $this->trialClass->language,
			'course'              => $this->trialClass->course,
			'speakingLevel'       => ($speakingLevel) ? $speakingLevel->level : null,
			'speakingLevelTitle'  => $this->evaluationData['speakingLevel'],
			'speakingLevelID'     => ($speakingLevel) ? $speakingLevel->evaluationLevelsID : null,
			'listeningLevel'      => ($listeningLevel) ? $listeningLevel->level : null,
			'listeningLevelTitle' => $this->evaluationData['listeningLevel'],
			'listeningLevelID'    => ($listeningLevel) ? $listeningLevel->evaluationLevelsID : null,
			'writingLevel'        => ($writingLevel) ? $writingLevel->level : null,
			'writingLevelTitle'   => $this->evaluationData['writingLevel'],
			'writingLevelID'      => ($writingLevel) ? $writingLevel->evaluationLevelsID : null,
			'readingLevel'        => ($readingLevel) ? $readingLevel->level : null,
			'readingLevelTitle'   => $this->evaluationData['readingLevel'],
			'readingLevelID'      => ($readingLevel) ? $readingLevel->evaluationLevelsID : null,
			'comments'            => $this->evaluationData['comments']
		]);
	}
}
