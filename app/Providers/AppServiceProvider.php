<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Absence;
use App\Models\AbsenceApproval;
use App\Models\TimeEvent;
use App\Models\TimeEventApproval;
use App\Observers\AbsenceObserver;
use App\Observers\AbsenceApprovalObserver;
use App\Observers\TimeEventObserver;
use App\Observers\TimeEventApprovalObserver;

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

        // Observer untuk model TimeEvent
        TimeEvent::observe(TimeEventObserver::class);

        // Observer untuk model TimeEventApproval
        TimeEventApproval::observe(TimeEventApprovalObserver::class);


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
