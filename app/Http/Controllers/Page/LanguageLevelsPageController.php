<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\PageController;

class LanguageLevelsPageController extends PageController
{
	public function __invoke()
	{
		// For the time being redirect to the legacy page
		return redirect( '/language-levels.php' );
	}
}
