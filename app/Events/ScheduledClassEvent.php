<?php

namespace App\Events;


use App\Event;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScheduledClassEvent
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $event;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct( Event $event )
	{
		$this->event = $event;
	}
}
