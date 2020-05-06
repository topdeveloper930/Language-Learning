<?php

namespace App\Http\Requests;

use App\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StatsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Super Admin and Admin are always allowed to.
     *
     * @return bool
     */
    public function authorize()
    {
	    return Auth::user() AND Auth::user()->hasAdminPermit();
    }
}
