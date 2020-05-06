<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

abstract class UserUpdateRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can( 'update', $this->inst );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
	    return $this->getRules();
    }

    abstract public function runUpdates();

	protected function prepareForValidation()
	{
		$this->offsetSet('inst', app( $this->instClass )->findOrFail( (int) $this->route('id') ) );
	}

	abstract protected function getRules();
}
