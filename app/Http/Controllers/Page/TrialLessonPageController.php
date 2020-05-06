<?php


namespace App\Http\Controllers\Page;


use App\Http\Controllers\PageController;

class TrialLessonPageController extends PageController {
    
    protected $template = 'page.trial_lesson';
    protected function make()
    {
        parent::make();
        $this->tplConfig->addJS( ['jquery', 'rangeslider', 'flexslider',  'jquery_ui', 'trial_lesson'] );
        $this->tplConfig->addCSS('datepicker');
    }

    protected function setTemplateName()
	{
        $step_id = (int) request()->route()->parameter( 'id' );
        $this->setParam('subPage', snake_case( str_replace( 'PageController', '', $this->classShortName() . $step_id)));
	}
}