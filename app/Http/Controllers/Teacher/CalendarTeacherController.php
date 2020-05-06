<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\TeacherController;

class CalendarTeacherController extends TeacherController
{
	/**
	 * TODO: Implement the functionality
	 *
	 * For the time being we just redirect to the older page.
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function __invoke( $id = null )
	{
		return redirect('/teachers/calendar.php' );
	}
}
