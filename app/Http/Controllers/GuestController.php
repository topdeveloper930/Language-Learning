<?php

namespace App\Http\Controllers;


abstract class GuestController extends TemplateController
{
	protected function make()
	{
		parent::make();
		$this->setParam( 'user', $this->user );
	}
}
