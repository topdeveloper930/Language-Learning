<?php

namespace App\Http\Controllers\Page;

use \App\Http\Controllers\PageController;
use App\Ip2Nation;
use App\JobApplicant;
use App\LanguageMaster;
use App\LearningQuizResult;
use App\Services\Auth\UserType;
use App\Teacher;
use DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class QuizPageController extends PageController {

	protected function make()
	{
		parent::make();

		$quizLanguage = ucfirst(Arr::get($this->arguments, 0));

		if($quizLanguage) {
			$this->tplConfig->removeJS(['slideout_menu']);
			$this->tplConfig->addJS(['jquery', 'rangeslider' , 'quiz']);
		}

		$this->setParams([
			'userType'     => 'student',
			'quizLanguage' => ''
		]);

		if(!$quizLanguage)
			$this->home();
		elseif (!strcasecmp('learning', $quizLanguage))
			$this->learning();
		elseif (!strcasecmp('teaching', $quizLanguage) OR !strcasecmp('teacher', $quizLanguage))
			$this->teaching();
		elseif(!strcasecmp('general', $quizLanguage) OR in_array(
			$quizLanguage,
			LanguageMaster::where([ [ 'active', 1 ], [ 'taught', 1 ] ])->pluck('name')->toArray()
		))
			$this->forLanguage($quizLanguage);
		else
			abort(404);
	}

	protected function obtainData()
    {
        parent::obtainData();

        $this->data = ('POST' == request()->method())
	        ? $this->processAnswers()
	        : $this->getQuestions();
    }

    private function processAnswers()
    {

	    /**
	     * If is set jobID in session, then save the result for the job applicant.
	     * If there is authenticated teacher, then save the result for the teacher.
	     *
	     * @var UserType $user
	     * @var Teacher $teacher
	     */
	    if( 'teacher' == $this->arguments[0] ) {
		    $user = auth('teacher')->user();

		    abort_unless( $user AND $teacher = $user->getInstance(), 400 );
	    }
	    elseif ( 'teaching' == $this->arguments[0] ) {
		    $jobID = request()->session()->get('jobID');
	    }

	    $quizLanguage = $quizLanguage = ucfirst(Arr::get($this->arguments, 0));

	    if(! in_array(
		    $quizLanguage,
		    LanguageMaster::where([ [ 'active', 1 ], [ 'taught', 1 ] ])->pluck('name')->toArray()
	    )) $quizLanguage = null;

	    $ip = (!empty( $_SERVER['REMOTE_ADDR'] )) ? $_SERVER['REMOTE_ADDR'] : null;

	    $country = ($ip) ? Ip2Nation::countryByIP($ip) : null;

	    $quizResult = LearningQuizResult::processAnswers( request()->all(), $quizLanguage, $ip, $country);

	    if( isset($teacher) )
	    	$teacher->update(['quiz_result_id' => $quizResult->getKey()]);
	    elseif ( isset($jobID) )
	    	JobApplicant::find($jobID)->update(['quiz_result_id' => $quizResult->getKey()]);

        return [
            'result_url' => route('page', ['controller' => 'quiz_result', 'id' => $quizResult->getKey() ])
        ];
    }

	private function getQuestions() {

		$query = DB::table( 'learningQuiz' )->where( 'active', 1 );

		if ( 'learning' == $this->arguments[0] ) {
			return $query->where( 'type', 'learning' )->inRandomOrder()->get();
		}
		elseif ( 'teacher' == $this->arguments[0] or 'teaching' == $this->arguments[0] ) {
			return $query->where( function ( $query ) {
				$query->orWhere( 'type', 'jungian' )
				      ->orWhere( 'type', 'teacher' );
			} )->inRandomOrder()->get();
		}

		return $query->where( function ( $query ) {
			$query->orWhere( 'type', 'jungian' )
			      ->orWhere( 'type', 'learning' );
		} )->inRandomOrder()->get();
	}

	private function home()
	{
		$this->setParams( [
			'subPage'      => 'quiz_home',
			'languageList' => LanguageMaster::where( [
				[ 'active', 1 ],
				[ 'taught', 1 ]
			] )->orderBy( 'position' )->get()
		] );
	}

	private function learning()
	{
		// Nothing to do
	}

	private function forLanguage( $language )
	{
		$this->setParams([
			'quizLanguage' => (!strcasecmp('general', $language)) ? '' : $language,
			'translation'  => 'quiz.student'
		]);
	}

	private function teaching()
	{
		if('teacher' == $this->arguments[0] AND !auth('teacher')->user()) {
			/**
			 * We cannot directly set current page as intended because the logging in function checks if the "role"
			 * route parameter exists prior to redirecting to the intended url and, if not, redirects to the dashboard page.
			 */
			request()->session()->put('url.intended', route('teachers', ['controller' => 'quiz']));
			$this->redirectResponse = redirect(route('login', ['role' => 'teachers']));
			return;
		}
		elseif ( request('jobID') ) {
			request()->session()->put('jobID',request('jobID'));
		}

		$this->setParams([
			'translation'   => 'quiz.teacher',
			'userType'      => 'teacher'
		]);
	}
}
