<?php

namespace App\Providers;

use App\Extensions\StudentUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
	    'App\Student'          => 'App\Policies\StudentPolicy',
	    'App\Teacher'          => 'App\Policies\TeacherPolicy',
	    'App\Event'            => 'App\Policies\EventPolicy',
	    'App\CalendarExternal' => 'App\Policies\CalendarExternalPolicy',
	    'App\Transaction'      => 'App\Policies\TransactionPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

	    // add custom guard provider
	    Auth::provider('student', function ($app, array $config) {
		    return new StudentUserProvider( $app['hash'], $config['model'] );
	    });

	    // Add custom Guards
	    foreach(['admin', 'teacher', 'student', 'group'] as $item) {
		    Auth::extend( $item, function ($app, $name, array $config ) {
			    $guardName = 'App\Services\Auth\\' . ucfirst( $name ) . 'Guard';

			    $guard = new $guardName( $name, Auth::createUserProvider( $config['provider'] ), request()->session(), $app->make('request') );

			    $guard->setDispatcher( $app->make( 'events' ) );

			    return $guard;
		    });
	    }
    }
}
