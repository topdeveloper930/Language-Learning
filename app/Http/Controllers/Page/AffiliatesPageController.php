<?php

namespace App\Http\Controllers\Page;

use Illuminate\Support\Arr;
use App\Notifications\Affiliate;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Validator;


class AffiliatesPageController extends PageController {

    protected function before()
    {
        parent::before();
        if ( 'POST' == request()->method() ) {
            $this->sendEmail();
            $this->redirectResponse = redirect(route('page', ['controller' => 'affiliates']) . "#email-form");
        }
       
       
    }

    public function sendEmail(){
        $validator = Validator::make(request()->all(), [
			'user_email' => 'required|email|max:150',
			'user_name'  => 'required|string|max:255',
			'note'  => 'max:512'
        ]);
        
        $validator->after(function ($validator) {
        
        $coordinator = \App\User::where('email', config( 'legacy.globals.mainEmail' ))->first();
        
        if( $coordinator )
        {
            $user_name = Arr::get( $validator->attributes(), 'user_name' );
            $user_email = Arr::get( $validator->attributes(), 'user_email' );
            $note = Arr::get( $validator->attributes(), 'note' );
            
            $coordinator->notify( new Affiliate($user_name, $user_email, $note));
			$this->notification(__('affiliate.affiliate_sent'), 'success');
        }
		});

		if ( $validator->fails() )
            throw new ValidationException( $validator );
            
    }
}
