<?php


namespace App\Http\Requests\API;


use App\Http\Requests\SystemRequest;

class ActiveCampaignRequest extends SystemRequest {

	/**
	 * Get the validation rules that apply to the request.
	 * TODO: depending on the method and action
	 *
	 * @return array
	 */
	public function rules()
	{
		return [];
//		return [
//			'student'   => 'integer',
//			'title'     => 'max:10',
//			'firstName' => 'max:150',
//			'lastName'  => 'max:150',
//			'email'     => 'email|unique:students|max:150',
//			'note'      => 'max:512'
//		];
	}
}