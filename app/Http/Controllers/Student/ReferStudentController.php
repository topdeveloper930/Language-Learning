<?php

namespace App\Http\Controllers\Student;

use App\Config;
use App\Http\Controllers\StudentController;
use App\ReferralInvitation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ReferStudentController extends StudentController
{
	protected $js = [ 'jquery3_4', 'dataTables', 'student_refer' ];

	protected $translation = 'student_refer.js';

	protected function make()
	{
		parent::make();

		$this->setParam( 'programConfig', Config::overriddenValuesByName( 'referral_program' ) );
	}

	protected function obtainData()
	{
		parent::obtainData();

		if ( 'POST' == request()->method() ) {
			$this->invite();
		}
		else {
			try{
				$this->{request()->route()->parameter('id')}();
			}
			catch( \BadMethodCallException $e ){
				throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
			}
		}
	}

	private function invite()
	{
		$validator = Validator::make(request()->all(), [
			'email' => 'required|email|unique:referral_invitations|max:150',
			'name'  => 'required|string|max:255',
			'note'  => 'max:512'
		], ['unique' => __('student_refer.unique', ['email' => request('email')])]);

		$validator->after(function ($validator) {
			if( $this->user->referralInvitationsTodayTotal() >= config( 'referral_program.referral_emails_max' ) ) {
				$validator->getMessageBag()->add('emails_max', __( 'student_refer.js.limit_reached', ['limit' => config( 'referral_program.referral_emails_max' )] ));
			}
		});

		if ( $validator->fails() )
			throw new ValidationException( $validator );

		$invite = new ReferralInvitation(request()->all());

		$this->data = $this->user->referralInvitations()->save( $invite );
	}

	private function referrals()
	{
		$this->data = $this->user->getReferralsWithBonuses();
	}

	private function credits()
	{
		$this->data = $this->user->getReferralCreditsHistory();
	}

	private function invitations()
	{
		$this->data = $this->user->invitedFriends();
	}
}
