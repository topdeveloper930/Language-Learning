<?php

namespace App\Http\Controllers;


abstract class PageController extends GuestController
{
	protected $js = ['slideout_menu'];

	/**
	 * If the template is explicitly set, then it is used.
	 * Otherwise default "common" template is used.
	 * Default sub template name is a snakecased name of the class without the PageController suffix.
	 * The sub template located in the corresponding language folder of the 'page' folder of views.
	 *
	 * @throws \ReflectionException
	 */
	protected function setTemplateName()
	{
		if( is_null( $this->template ) ) {
			$this->template = 'page.common';
			$this->setParam('subPage', snake_case( str_replace( 'PageController', '', $this->classShortName() )));
		}
	}
}
