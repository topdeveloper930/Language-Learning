<?php

namespace App\Http\Requests;


class CreditRequest extends StudentRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
	        'course' => 'required|max:50|exists:courseList,courseType'
        ];
    }

	protected function prepareForValidation()
	{
		$this->getInputSource()->add([
			'course' => $this->course
		]);
	}
}
