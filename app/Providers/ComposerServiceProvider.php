<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Yajra\DataTables\Html\Builder;

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
        View::composer('layouts._sidebar-user', 
            'App\Http\ViewComposers\SidebarUserComposer');

        // view composer untuk sidebar-secretary
        View::composer('layouts.secretary._sidebar', 
            'App\Http\ViewComposers\SidebarSecretaryComposer');
        
        // view composer untuk dashboards.employee
        View::composer('dashboards.employee', 
            'App\Http\ViewComposers\EmployeeDashboardComposer');

        // view composer untuk dashboards.approval
        View::composer('dashboards.approval', 
            'App\Http\ViewComposers\EmployeeApprovalComposer');
   }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // DataTables builder for leave approval
        $this->app->bind('datatables.html.leaveTable', function () {
            return $this->app->make(Builder::class);
        });

        // DataTables builder for permit approval
        $this->app->bind('datatables.html.permitTable', function () {
            return $this->app->make(Builder::class);
        });        

        // DataTables builder for attendance quota approval
        $this->app->bind('datatables.html.overtimeTable', function () {
            return $this->app->make(Builder::class);

        });     

        // DataTables builder for attendance quota approval
        $this->app->bind('datatables.html.skiTable', function () {
            return $this->app->make(Builder::class);

        });    

        // DataTables builder for time event approval
        $this->app->bind('datatables.html.timeEventTable', function () {
            return $this->app->make(Builder::class);
        });   
    }
}