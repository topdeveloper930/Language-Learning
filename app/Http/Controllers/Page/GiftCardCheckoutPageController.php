<?php

namespace App\Http\Controllers\Page;

use \App\Http\Controllers\PageController;

use App\Components\Payment\Classes\PaymentGateway;
use App\Http\Controllers\StudentController;
use App\Transaction;
use App\TrialClassLog;
use Illuminate\Support\Facades\Cookie;
use Payment;


class GiftCardCheckoutPageController extends PageController {

    protected $translation = 'gift_card_checkout.js';

    protected function make()
    {
        parent::make();
        $this->tplConfig->addJS( ['jquery3_4', 'stripe_sdk', 'typeahead', 'gift_card_checkout'] );
        $this->setParams($this -> getPaymentData());
        
    }

    protected function before()
	{
        parent::before();
        'GET' == request()->method() ? $this->redirectResponse = redirect(route('page', ['controller' => 'gift-cards'])) : '';
    }

    protected function obtainData()
    {
        parent::obtainData();
 
		// If no paymentMethod param set, then it's balance request. Either driver fits.
        // Let's set Transfer with minimum extra configs and functionality.
        if('POST' == request()->method())
        {
            
            $this->data = Payment::gateway( request('paymentMethod') )
                             ->send();
        }
		
                             
    }

    function getPaymentData()
    {
      
        return [
            'recipient' => [    'name' =>  request() -> recipient_sex 
                                . request() -> recipient_firstName . " " 
                                . request() -> recipient_lastName,
                                'email' => request() -> recipient_email ],

            'purchaser' => [    'name' => request() -> purchaser_sex 
                                . request() -> purchaser_firstName . " " 
                                . request() -> purchaser_lastName,
                                'email'  => request() -> purchaser_email   ],

            'credit'    => [    'amount' => request() -> creditAmount,
                                'discountAmount' => request() -> discountAmount   ],

            'option'    => [    'method' => request() -> deliveryMethod,
                                'sendDate' => request() -> sendDate    ],
                        
            'giftCardCategory' => request() -> giftCardCategory,
            'message'          => request() -> comments
        ];


    }


}
