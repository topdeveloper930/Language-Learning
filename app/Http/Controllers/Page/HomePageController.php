<?php


namespace App\Http\Controllers\Page;

use App\Http\Controllers\PageController;

class HomePageController extends PageController {
    protected function make()
    {
        parent::make();
        $this->tplConfig->addJS( ['jquery', 'flexslider', 'flexslider_component'] );
    }
}