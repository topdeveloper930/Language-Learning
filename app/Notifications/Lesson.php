<?php

namespace App\Notifications;

use App\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class Lesson extends Notification implements ShouldQueue
{
    use Queueable;

    public $event;
    public $_by;

    /**
     * Create a new notification instance.
     *
     * @param Event    $event
     *
     * @return void
     */
    public function __construct( Event $event )
    {
        $this->event = $event;
        $this->_by   = ( $event->updated_by )
	        ? substr( $event->updated_by, 0, strpos( $event->updated_by, ':' ) )
            : substr( $event->createdBy, 0, strpos( $event->createdBy, ':' ) );
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
}
