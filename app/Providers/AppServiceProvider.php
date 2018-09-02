<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Absence;
use App\Models\AbsenceApproval;
use App\Observers\AbsenceObserver;
use App\Observers\AbsenceApprovalObserver;

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

        // Observer untuk model Absence
        Absence::observe(AbsenceObserver::class);

        // Observer untuk model AbsenceApproval
        AbsenceApproval::observe(AbsenceApprovalObserver::class);


        \Form::macro('labelRadio', function ($name, $value, $label,
            $labelAttributes = [], $radioAttributes = [])
        {
            $labelData = $radioData = '';

            foreach ($labelAttributes as $a => $b) {
                $labelData .= $a . "='" . $b . "' ";
            }
        
            foreach ($radioAttributes as $a => $b) {
                $radioData .= $a . "='" . $b . "' ";
            }
        
            $html = "<label $labelData>
                        <input name='$name' type='radio' value='$value' $radioData /> $label
                    </label>";
        
            return $html;
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
