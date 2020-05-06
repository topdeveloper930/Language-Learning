<?php

namespace App\Http\Requests;

use App\Event;
use App\Student;
use Illuminate\Support\Facades\Auth;

class CreateEventRequest extends EventRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	return Auth::user()->can('create', (new Event())->fill( request()->all() ) );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'teacherID' => 'required|integer',
            'studentID' => 'required|integer',
	        'eventStart' => 'bail|required|date|after:tomorrow',
	        'eventEnd'  => 'bail|required|date|after:eventStart|event_no_conflict:eventStart,,teacherID',
	        'course'    => 'required|max:50|exists:courseList,courseType',
	        'numStudents' => 'integer|between:1,3'
        ];
    }

	protected function prepareForValidation() {}

	public function enoughCredit()
	{
		$class_length = ( strtotime( $this->eventEnd ) - strtotime(  $this->eventStart ) ) / 3600;

		return Student::find( $this->studentID )->remainingCredits( $this->course, $this->numStudents ) >= $class_length;
	}

	public function withValidator( $validator )
	{
		parent::withValidator( $validator );

		if( $this->studentID AND $this->course ) {
			$this->getInputSource()->add([
				'entryTitle' => Student::find( $this->studentID )->courseTitle( $this->course ),
				'entryType' => 'Student',
				'createdBy' => $this->guardName() . ':' . $this->currentUserID()
			]);
		}
	}
}
