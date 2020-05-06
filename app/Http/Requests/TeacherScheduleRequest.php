<?php

namespace App\Http\Requests;

use App\Teacher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class TeacherScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
	    $this->teacher = Teacher::find( $this->id );

	    return $this->teacher AND Gate::allows( 'view', $this->teacher );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
	        'start' => 'required|date',
	        'end'   => 'required|date|after:start',
        ];
    }
}
