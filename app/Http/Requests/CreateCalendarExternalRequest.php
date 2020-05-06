<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateCalendarExternalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
	    return Auth::user()->can('create', new \App\CalendarExternal );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        	'provider' => [
        		'required',
		        'in:google',
		        Rule::unique('calendar_external')
		            ->where('user_id', $this->user_id )
			        ->where('user_type', $this->user_type )
	        ],
	        'provider_cal_id'   => 'required|max:256',
	        'authentication_code' => 'required'
        ];
    }

	protected function prepareForValidation()
	{
		$user = Auth::user();

		if( $user->teacher ){
			$user_id = $user->teacher->teacherID;
			$user_type = 'teacher';
		}
		else {
			$user_id = $user->studentID;
			$user_type = 'student';
		}

		$this->getInputSource()->add([
			'user_id' => $user_id,
			'user_type' => $user_type
		]);
	}
}
