<?php

namespace App\Http\Requests;

use App\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentUpdateRequest extends UserUpdateRequest
{
	protected $instClass = \App\Student::class;

    public function runUpdates()
    {
	    switch ( $this->route('form') )
	    {
		    case 'profile':
		    case 'settings':
			    return $this->inst->fill( $this->only( array_keys( $this->rules() ) ) )->save();
		    case 'security':
			    return $this->inst->fill([ 'password' => Hash::make(request( 'password' )) ])->save();
		    default:
			    return false;
	    }
    }

	/**
	 * Configure the validator instance.
	 *
	 * @param  \Illuminate\Validation\Validator  $validator
	 * @return void
	 */
	public function withValidator($validator)
	{
		if( 'security' == $this->route('form') AND Student::TYPE == Auth::user()->getType() ) {
			$validator->after(function ( $validator ) {
				if( !password_verify(request( 'current_password' ), Auth::user()->password) ) {
					$validator->errors()->add('current_password', __( 'auth.failed' ));
				}
			});
		}
	}
	protected function getRules()
	{
		$form = $this->route('form');

		$rules = [
			'security' => [
			    'current_password' => 'required',
			    'password' => 'required|string|min:8|confirmed'
		    ],
			'profile' => [
				'title'       => 'required|in:' . implode( ',', array_keys( __( 'student_profile.titles' ) ) ),
				'firstName'   => 'required|string|max:150',
				'lastName'    => 'required|string|max:150',
				'timezone'    => 'required|timezone',
				'dateOfBirth' => 'nullable|date',
				'information' => 'nullable|string|max:5000',
				'skype'       => 'required|string|max:150',
				'phone'       => 'nullable|phone',
				'paypalEmail' => 'nullable|email|max:150',
				'country'     => 'required|exists:locationCountries,name',
				'state'       => 'required|exists:locationRegions,name',
				'city'        => 'required|string|max:150'
		    ],
			'settings' => [
				'mailingList'      => 'required|in:Active,Inactive',
				'classLogMessages' => 'required|in:Active,Inactive',
				'classReminder'    => 'required|in:Active,Inactive',
			]
		];

		abort_unless( isset( $rules[ $form ] ), 422 );

		return $rules[ $form ];
	}
}
