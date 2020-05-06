<?php

namespace App\Listeners;

use App\Events\CompletedPaymentEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompletedPayment implements ShouldQueue
{
	use Queueable, InteractsWithQueue;


    /**
     * Handle the event.
     *
     * @param  CompletedPaymentEvent $event
     * @return void
     */
    public function handle( CompletedPaymentEvent $event )
    {
        //TODO
    }
}
