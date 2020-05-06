<?php

namespace App\Listeners;

use App\Events\CalendarIntegrationEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SyncCalendar implements ShouldQueue
{
	use Queueable, InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  CalendarIntegrationEvent  $event
     * @return void
     * @throws \Exception
     */
    public function handle( CalendarIntegrationEvent $event )
    {
	    $event->calendar->sync();
    }
}
