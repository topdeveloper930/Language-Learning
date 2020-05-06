<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;

class DashboardAdminController extends AdminController
{
	public function __invoke()
	{
		// For time being redirect to the legacy page
		return redirect( '/admin/admin-dashboard.php' );
	}
}
