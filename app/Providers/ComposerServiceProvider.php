<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // view composer untuk sidebar-user
        View::composer('layouts._sidebar-user', 'App\Http\ViewComposers\SidebarUserComposer');

        // view composer untuk employee-sidebar
        View::composer('layouts.employee._sidebar', 'App\Http\ViewComposers\EmployeeSidebarComposer');
        
        // view composer / view creator tidak dapat menangkap exception
        // sehingga dipindahkan ke controller saja T_T
        // // view composer untuk formulir cuti
        // View::composer('leaves._form', 'App\Http\ViewComposers\LeaveFormComposer');
        // // view creator untuk formulir cuti
        // View::creator('leaves._form', 'App\Http\ViewCreators\LeaveFormCreator');

        // // Using Closure based composers...
        // View::composer('layouts._sidebar', function ($view) {

        // });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}