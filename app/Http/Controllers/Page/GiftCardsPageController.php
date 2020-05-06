<?php

namespace App\Http\Controllers\Page;

use \App\Http\Controllers\PageController;

class GiftCardsPageController extends PageController {

    protected function make()
    {
        parent::make();
        $this->tplConfig->addJS( ['gift_cards'] );



        $this->setParams([
            'discountInfo' => $this -> getDiscountInfo()
        ]);
    }

    private function getDiscountInfo()
    {
        $date = time();
        $discountStart = strtotime('2019-12-15 00:00:00');
        $discountEnd = strtotime('2020-5-1 23:59:59');
        $discount = false;
        if ($date > $discountStart && $date < $discountEnd ) {  
            $discount=true;
          }

        if($discount==TRUE){
            $discountAmount = 0.90; 
            $discountMessage="<i>10% Off - Happy Holidays</i>";  
        }else{
            $discountAmount = 1;
        }

        return [
            'discount' => $discount,
            'discountAmount' => $discountAmount,
            'discountMessage' => $discountMessage
            ];
    }
}
