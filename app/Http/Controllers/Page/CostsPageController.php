<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\LanguagePageController;

class CostsPageController extends LanguagePageController
{
    protected function make()
    {
        parent::make();
        $this->tplConfig->addJS( ['jquery', 'rangeslider', 'flexslider', 'flexslider_component', 'pricing'] );
    }
}
