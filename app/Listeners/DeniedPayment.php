<?php

namespace App\Listeners;

use App\Events\DeniedPaymentEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeniedPayment implements ShouldQueue
{
	use Queueable, InteractsWithQueue;


    /**
     * Handle the event.
     *
     * @param  DeniedPaymentEvent $event
     * @return void
     */
    public function handle( DeniedPaymentEvent $event )
    {
        //TODO
    }
}
