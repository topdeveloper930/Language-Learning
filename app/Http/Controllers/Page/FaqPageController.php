<?php


namespace App\Http\Controllers\Page;


use App\Http\Controllers\PageController;

class FaqPageController extends PageController {
    protected function make()
    {
        parent::make();
        $this->tplConfig->addJS( ['jquery', 'faq'] );
    }
}