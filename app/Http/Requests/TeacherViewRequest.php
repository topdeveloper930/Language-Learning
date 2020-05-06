<?php

namespace App\Http\Requests;

use App\Teacher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class TeacherViewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
	    $t = new Teacher;
	    $is_email = (bool) filter_var( $this->id, FILTER_VALIDATE_EMAIL );
	    $this->teacher = $t->getByIDorEmail( $this->id, $is_email);

	    return Gate::allows( 'view', $this->teacher );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
	    $rules = [
		    'tz' => 'timezone'
	    ];

	    $email = filter_var( $this->id, FILTER_VALIDATE_EMAIL );

	    if( $this->id AND !$email ) {
		    $rules[ 'id' ] = 'required|numeric';
	    }
	    elseif ( $email ) {
	    	$rules[ 'id' ] = 'required|email';
	    }
	    else {
	    	$this->id = $this->email;
		    $rules[ 'email' ] = 'required|email';
	    }

        return $rules;
    }

    protected function prepareForValidation()
    {
	    $this->getInputSource()->replace([
	    	'id'    => $this->id,
		    'email' => $this->email
	    ]);

	    if( $this->tz )
		    $this->getInputSource()->add([
			    'tz'    => ( ( $this->tz_2 ) ? $this->tz . '/' . $this->tz_2 : $this->tz )
		    ]);
	    else
		    $this->getInputSource()->add([ 'tz'    => 'UTC' ]);
    }

	public function validate()
	{
		$this->prepareForValidation();

		$instance = $this->getValidatorInstance();

		if (! $instance->passes())
			$this->failedValidation($instance);
		elseif (! $this->passesAuthorization())
			$this->failedAuthorization();
	}

	/**
	 * Custom message for validation
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'id.required'   => 'The teacher ID required and must be either number or email.',
			'id.numeric'    => 'The provided teacher ID value must be either number or email.',
			'id.email'      => 'The provided teacher ID value must be either number or email.'
		];
	}
}
