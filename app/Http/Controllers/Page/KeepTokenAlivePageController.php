<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\PageController;

class KeepTokenAlivePageController extends PageController
{
    protected function obtainData()
    {
	    parent::obtainData();

	    $this->data = 'Token must have been valid, and the session expiration has been extended.';
    }
}
