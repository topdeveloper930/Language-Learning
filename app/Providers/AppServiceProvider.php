<?php

namespace App\Providers;

use App\ClassLog;
use App\Evaluation;
use App\Event;
use App\Observers\ClassLogObserver;
use App\Observers\EvaluationObserver;
use App\Observers\EventObserver;
use App\Observers\ReferralInvitationObserver;
use App\ReferralInvitation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	    Schema::defaultStringLength(191);

	    Validator::extend('event_no_conflict', 'App\Rules\EventRules@noConflict');
	    Validator::extend('event_after', 'App\Rules\EventRules@after');
	    Validator::extend('phone', 'App\Rules\Valid@phone');
	    Validator::extend('timezone', 'App\Rules\Valid@timezone');

	    Event::observe( EventObserver::class );
	    ReferralInvitation::observe( ReferralInvitationObserver::class );
	    ClassLog::observe( ClassLogObserver::class );
	    Evaluation::observe( EvaluationObserver::class );

	    Blade::directive('dboutput', function ($expression) {
		    return "<?php echo nl2br( str_replace( '\\\', '', {$expression } ) ); ?>";
	    });

	    Blade::directive('fromparentheses', function ($expression) {
		    return "<?php echo substr({$expression }, strpos({$expression }, '(') + 1, strpos({$expression }, ')') - strpos({$expression }, '(') - 1 ); ?>";
	    });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
