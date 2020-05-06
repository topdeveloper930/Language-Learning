<?php

namespace App\Listeners;

use App\Events\ScheduledClassEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SyncEvent implements ShouldQueue
{
	use Queueable, InteractsWithQueue;


    /**
     * Handle the event.
     *
     * @param  ScheduledClassEvent  $event
     * @return void
     */
    public function handle( ScheduledClassEvent $event )
    {
        $event->event->sync();
    }
}
