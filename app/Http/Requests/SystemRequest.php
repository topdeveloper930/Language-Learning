<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SystemRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 * Since this is system requests to be made by system user with Super Admin role, then only SuperAdmin user has permit.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->isSuperAdmin();
	}
}