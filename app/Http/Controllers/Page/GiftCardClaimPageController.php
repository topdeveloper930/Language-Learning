<?php

namespace App\Http\Controllers\Page;

use \App\Http\Controllers\PageController;

class GiftCardClaimPageController extends PageController {

    protected function make()
    {
        parent::make();
        $this->tplConfig->addJS( ['gift_card_claim'] );
    }
}
