<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Absence;
use App\Models\AbsenceApproval;
use App\Models\Attendance;
use App\Models\AttendanceApproval;
use App\Models\AttendanceQuota;
use App\Models\AttendanceQuotaApproval;
use App\Models\TimeEvent;
use App\Models\TimeEventApproval;
use App\Observers\
    { AbsenceObserver, AbsenceApprovalObserver,
      TimeEventObserver, TimeEventApprovalObserver,
      AttendanceObserver, AttendanceApprovalObserver,
      AttendanceQuotaObserver, AttendanceQuotaApprovalObserver };

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // https
        if(config('app.env') === 'production'){
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        
        // Fix for doctrine using mariaDB
        Schema::defaultStringLength(191);

        // locale Carbon
        config(['app.locale' => 'id']);
        \Carbon\Carbon::setLocale('id');

        // Observer untuk model Absence
        Absence::observe(AbsenceObserver::class);
        // Observer untuk model AbsenceApproval
        AbsenceApproval::observe(AbsenceApprovalObserver::class);

        // Observer untuk model Attendance
        Attendance::observe(AttendanceObserver::class);
        // Observer untuk model AttendanceApproval
        AttendanceApproval::observe(AttendanceApprovalObserver::class);

        // Observer untuk model AttendanceQuota
        AttendanceQuota::observe(AttendanceQuotaObserver::class);
        // Observer untuk model AttendanceQuotaApproval
        AttendanceQuotaApproval::observe(AttendanceQuotaApprovalObserver::class);

        // Observer untuk model TimeEvent
        TimeEvent::observe(TimeEventObserver::class);
        // Observer untuk model TimeEventApproval
        TimeEventApproval::observe(TimeEventApprovalObserver::class);

        // Form macro laravelcollective
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
    
    }
}
