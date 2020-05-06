<?php

namespace App\Events;

use App\CalendarExternal;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CalendarIntegrationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $calendar;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( CalendarExternal $calendar )
    {
        $this->calendar = $calendar;
    }
}
