<?php

namespace App\Listeners;

use \Illuminate\Auth\Events\Login;

class UserLoginListener
{

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     * @throws \Exception
     */
    public function handle( Login $event )
    {
	    $event->user->getInstance()->logLogin();
    }
}
