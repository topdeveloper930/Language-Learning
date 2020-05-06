<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

class StatsTeacherAvailabilityAdminController extends AdminController
{
    protected $permitted_roles = [ 'super-admin', 'admin' ];

    protected $template = 'admin.stats_teacher_availability';

	protected $translation = 'stats';

	protected $current_menu = 'stats.teacher_availability_stats';
	protected $page_title = 'stats.teacher_availability_stats';

	public function make()
	{
		parent::make();

		$this->tplConfig->addCSS( 'dataTables' );
		$this->tplConfig->addJS( [
			'jquery', 'dataTables', 'teacher_availability_stats'
		] );
	}
}
