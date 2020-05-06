<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
	    if ($request->is('students/*') && Auth::guard('student')->check()) {
		    return redirect('/students/dashboard');
	    }

	    if ($request->is('teachers/*') && Auth::guard('teacher')->check()) {
		    return redirect('/teachers/dashboard');
	    }

	    if ($request->is('admin/*') && Auth::guard('admin')->check()) {
		    return redirect('/admin/admin-dashboard.php');
	    }

        return $next($request);
    }
}
