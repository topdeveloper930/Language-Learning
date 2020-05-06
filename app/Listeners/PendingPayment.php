<?php

namespace App\Listeners;

use App\Events\PendingPaymentEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PendingPayment implements ShouldQueue
{
	use Queueable, InteractsWithQueue;


    /**
     * Handle the event.
     *
     * @param  PendingPaymentEvent $event
     * @return void
     */
    public function handle( PendingPaymentEvent $event )
    {
        //TODO
    }
}
