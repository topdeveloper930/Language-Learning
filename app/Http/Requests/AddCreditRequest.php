<?php

namespace App\Http\Requests;


class AddCreditRequest extends StudentRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
	        'course' => 'required|max:50|exists:courseList,courseType',
	        'hours' => 'required|numeric'
        ];
    }

	protected function prepareForValidation()
	{
		$this->getInputSource()->add([
			'student' => $this->student
		]);
	}
}
