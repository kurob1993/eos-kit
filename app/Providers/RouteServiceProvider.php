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

        $this->mapEmployeeRoutes();

        $this->mapSecretaryRoutes();

        $this->mapPersonnelServiceRoutes();

        $this->mapBasisRoutes();
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
        Route::prefix('api')
             ->middleware('api')
             ->namespace('App\Http\Controllers\API')
             ->group(base_path('routes/api.php'));
    }

    protected function mapEmployeeRoutes()
    {
        Route::group([
            'middleware' => ['web', 'auth', 'role:employee'],
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/employee.php');
        });
    }

    protected function mapSecretaryRoutes()
    {
        Route::group([
            'prefix' => 'secretary',
            'middleware' => ['web', 'auth:secr', 'role:secretary'],
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/secretary.php');
        });
    }

    protected function mapPersonnelServiceRoutes()
    {
        Route::group([
            'prefix' => 'personnel_service',
            'middleware' => ['web', 'auth', 'role:personnel_service'],
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/personnel_service.php');
        });        
    }

    protected function mapBasisRoutes()
    {
        Route::group([
            'prefix' => 'basis',
            'middleware' => ['web', 'auth', 'role:basis'],
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/basis.php');
        });        
    }    
}
