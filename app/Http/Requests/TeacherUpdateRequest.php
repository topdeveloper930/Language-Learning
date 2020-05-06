<?php

namespace App\Http\Requests;

use App\LocationCountries;
use App\LocationRegions;
use App\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherUpdateRequest extends UserUpdateRequest
{
	protected $instClass = \App\Teacher::class;

    public function runUpdates()
    {
	    switch ( $this->route('form') )
	    {
		    case 'personal':
			    $this->updateLocationIDs();
		    case 'profile':
		    case 'practice':
			    return $this->inst->fill( $this->only( array_keys( $this->rules() ) ) )->save();
		    case 'email':
			    return $this->inst->fill([ 'email' => $this->get( 'email' ) ])->save()
			           AND $this->inst->usuario->fill([ 'email' => $this->get( 'email' ) ])->save();
		    case 'paypal':
			    return $this->inst->fill([ 'paymentEmail' => $this->get( 'paymentEmail' ) ])->save();
		    case 'security':
			    return $this->inst->usuario->fill([ 'password' => Hash::make(request( 'password' )) ])->save();
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
		if( 'security' == $this->route('form') AND Teacher::TYPE == Auth::user()->getType() ) {
			$validator->after(function ( $validator ) {
				if( !password_verify(request( 'current_password' ), Auth::user()->password) ) {
					$validator->errors()->add('current_password', __( 'auth.failed' ));
				}
			});
		}
	}

	protected function getRules()
	{
		$thisYear = date('Y');
		$form = $this->route('form');

		$rules = [
			'email' => [
			    'email' => 'required|email|max:150|confirmed'
		    ],
			'paypal' => [
			    'paymentEmail' => 'required|email|max:150|confirmed'
		    ],
			'security' => [
			    'current_password' => 'required',
			    'password' => 'required|string|min:8|confirmed'
		    ],
			'profile' => [
			    'languagesSpoken' => 'nullable|string|max:500',
			    'agesTaught'      => 'required|array',
			    'coursesTaught'   => 'array'
		    ],
			'practice' => [
			    'teacherIntroduction'   => 'required|string|max:65535|min:150',
			    'startedTeaching'       => "required|integer|max:$thisYear|min:" . ($thisYear - 90),
			    'education'             => 'nullable|string|max:2000',
			    'teachingStyle'         => 'required|string|max:100',
			    'workExperience'        => 'nullable|string|max:65535',
			    'hobbies'               => 'nullable|string|max:1000',
			    'favoriteWebsite'       => 'nullable|string|max:255',
			    'favoriteMovie'         => 'nullable|string|max:255',
			    'favoriteFood'          => 'nullable|string|max:128',
			    'countriesVisited'      => 'nullable|string|max:500',
			    'bucketList'            => 'nullable|string|max:500',
		    ],
			'personal' => [
			    'title'            => 'required|in:' . implode( ',', array_keys( __( 'student_profile.titles' ) ) ),
			    'firstName'        => 'required|string|max:150',
			    'lastName'         => 'required|string|max:150',
			    'timezone'         => 'required|timezone',
			    'skype'            => 'required|string|max:150',
			    'phone'            => 'nullable|phone',
			    'newStudents'      => 'required|in:0,1',
			    'countryOrigin'    => 'required|exists:locationCountries,name',
			    'stateOrigin'      => 'required|exists:locationRegions,name',
			    'cityOrigin'       => 'required|string|max:150',
			    'countryResidence' => 'required|exists:locationCountries,name',
			    'stateResidence'   => 'required|exists:locationRegions,name',
			    'cityResidence'    => 'required|string|max:150',
			    'zipResidence'     => 'required|string|max:100',
			    'street1Residence' => 'nullable|string|max:150',
			    'street2Residence' => 'nullable|string|max:150'
		    ]
		];

		abort_unless( isset( $rules[ $form ] ), 422 );

		return $rules[ $form ];
	}

	private function updateLocationIDs()
	{
		if( $countryOrigin = $this->get( 'countryOrigin' ) AND $countryOrigin != $this->inst->countryOrigin )
			$this->inst->countryOriginID = LocationCountries::where( 'name', $countryOrigin )->first()->getKey();

		if( $stateOrigin = $this->get( 'stateOrigin' ) AND $stateOrigin != $this->inst->stateOrigin )
			$this->inst->stateOriginID = LocationRegions::where( [['name', $stateOrigin], ['id_country', $this->inst->countryOriginID]] )->first()->getKey();

		if( $countryResidence = $this->get( 'countryResidence' ) AND $countryResidence != $this->inst->countryResidence )
			$this->inst->countryResidenceID = LocationCountries::where( 'name', $countryResidence )->first()->getKey();

		if( $stateResidence = $this->get( 'stateResidence' ) AND $stateResidence != $this->inst->stateResidence )
			$this->inst->stateResidenceID = LocationRegions::where( [['name', $stateResidence], ['id_country', $this->inst->countryResidenceID]] )->first()->getKey();
	}
}
