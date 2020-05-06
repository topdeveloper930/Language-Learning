<?php


namespace App\Http\Controllers\Page;


use App\Http\Controllers\PageController;

class MarketingController extends PageController {
    protected $template = 'page.marketing';

    protected function setTemplateName()
	{
        $this->setParam('subPage', snake_case( str_replace( 'PageController', '', $this->classShortName() )));
	}

}