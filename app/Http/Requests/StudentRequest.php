<?php

namespace App\Http\Requests;

use App\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	$student = new Student;
    	$this->offsetSet('inst', $student->find( $this->student ));

	    return Auth::user()->can('delete', $this->inst);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
	    return [
		    'student'   => 'integer',
		    'title'     => 'max:10',
		    'firstName' => 'max:150',
		    'lastName'  => 'max:150',
		    'email'     => 'email|unique:students|max:150',
		    'note'      => 'max:512'
	    ];
    }
}
