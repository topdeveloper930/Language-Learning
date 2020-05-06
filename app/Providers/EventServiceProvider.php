<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
	    'App\Events\ScheduledClassEvent' => [
	    	'App\Listeners\SyncEvent'
        ],
	    'App\Events\CalendarIntegrationEvent' => [
		    'App\Listeners\SyncCalendar'
	    ],
	    'Illuminate\Auth\Events\Login' => [
		    'App\Listeners\UserLoginListener'
	    ],
	    'App\Events\PendingPaymentEvent' => [
		    'App\Listeners\PendingPayment'
	    ],
	    'App\Events\CompletedPaymentEvent' => [
		    'App\Listeners\CompletedPayment'
	    ],
	    'App\Events\DeniedPaymentEvent' => [
		    'App\Listeners\DeniedPayment'
	    ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
