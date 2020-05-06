<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
	    Route::middleware('web')
	         ->namespace($this->namespace)
	         ->group(base_path('routes/web/auth.php'));

	    Route::prefix('admin')
	         ->middleware([ 'web', 'auth:admin' ])
	         ->namespace($this->namespace . '\Admin')
	         ->group(base_path('routes/web/admin.php'));

	    Route::prefix('teachers')
	         ->middleware([ 'web', 'auth:teacher' ])
	         ->namespace($this->namespace . '\Teacher')
	         ->group(base_path('routes/web/teachers.php'));

	    Route::prefix('students')
	         ->middleware([ 'web', 'auth:student' ])
	         ->namespace($this->namespace . '\Student')
	         ->group(base_path('routes/web/students.php'));

	    Route::prefix('pub')
	         ->middleware([ 'web' ])
	         ->namespace($this->namespace . '\Ajax')
	         ->group(base_path('routes/web/pub.php'));

	    Route::prefix('ajax')
	         ->middleware([ 'web', 'auth:student,teacher,admin' ])
	         ->namespace($this->namespace . '\Ajax')
	         ->group(base_path('routes/web/ajax.php'));

	    Route::middleware(['web', 'affiliate'])
	         ->namespace($this->namespace . '\Page')
	         ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('')
             ->middleware('api')
             ->namespace($this->namespace . '\API')
             ->group(base_path('routes/api.php'));
    }
}
