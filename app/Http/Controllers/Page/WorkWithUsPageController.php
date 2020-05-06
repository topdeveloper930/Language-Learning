<?php

namespace App\Http\Controllers\Page;

use \App\Http\Controllers\PageController;

class WorkWithUsPageController extends PageController {

    protected function make()
    {
        parent::make();
        $this->tplConfig->addJS( ['jquery', 'flexslider', 'flexslider_component'] );
    }

    protected function setTemplateName()
	{
        $this->template = 'page.common';
        
        $quiz_id = (int) request()->route()->parameter( 'id' );
        if(request()->route()->parameter( 'id' ) == NULL)
            $this->setParam('subPage', 'work_with_us');
        else
            $this->setParam('subPage', 'work_with_us_single');
        $this->setParam('quiz_id', $quiz_id);
	}
}
