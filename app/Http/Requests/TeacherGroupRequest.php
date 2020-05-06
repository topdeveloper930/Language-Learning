<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class TeacherGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
	    return Auth::user()->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
	    return [
		    'id'    => 'required|integer',
		    'group' => 'required|integer',
		    'note'  => 'max:512'
	    ];
    }

	protected function prepareForValidation()
	{
		Input::merge([
			'id'    => $this->id,
			'group' => $this->group
		]);
	}
}
