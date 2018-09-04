<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Html\Builder;
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
        
        View::composer('dashboards.employee', function($view) {
            $absenceTableBuilder = app('datatables.html.absenceTable');
            $absenceTable = $absenceTableBuilder
            ->setTableAttribute('id', 'absenceTable')
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'absence_id', 'name' => 'absence_id', 'title' => 'Pengajuan', 'orderable' => false])
            ->addColumn(['data' => 'absence.user.personnel_no', 'name' => 'absence.user.personnel_no', 'title' => 'Karyawan', 'orderable' => false,])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Status', 'searchable' => false, 'orderable' => false])
            ->ajax(route('dashboards.absence_approval'));
            $view->with('absenceTable', $absenceTable);
        });

        View::composer('dashboards.employee', function($view) {
            $attendanceTableBuilder = app('datatables.html.attendanceTable');
            $attendanceTable = $attendanceTableBuilder
                ->setTableAttribute('id', 'attendanceTable')
                ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
                ->addColumn(['data' => 'attendance_id', 'name' => 'attendance_id', 'title' => 'Pengajuan', 'orderable' => false])
                ->addColumn(['data' => 'attendance.user.personnel_no', 'name' => 'attendance.user.personnel_no', 'title' => 'Karyawan', 'orderable' => false,])
                ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Status', 'searchable' => false, 'orderable' => false])
                ->ajax(route('dashboards.attendance_approval'));
            $view->with('attendanceTable', $attendanceTable);
        });        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('datatables.html.absenceTable', function () {
            return $this->app->make(Builder::class);
        });
        $this->app->bind('datatables.html.attendanceTable', function () {
            return $this->app->make(Builder::class);
        });        
    }
}
