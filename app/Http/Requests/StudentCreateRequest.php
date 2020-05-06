<?php

namespace App\Http\Requests;

use App\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class StudentCreateRequest extends FormRequest
{
	const DUMMY_EMAIL = 'dummy@mail.com';
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	return Auth::user()->can('create', Student::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
	        'title'     => 'max:10',
	        'firstName' => 'required|max:150',
	        'lastName'  => 'required|max:150',
	        'email'     => 'required|email|max:150|unique:students',
	        'group_id'  => 'required|integer'
        ];
    }

	protected function prepareForValidation()
	{
		$user = Auth::user();
		if ( !$user->isSuperAdmin() )
			Input::merge([ 'group_id' => $user->group->groupID ]);
	}
}
