<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Html\Builder;
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
        // Fix for doctrine using mariaDB
        Schema::defaultStringLength(191);

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
        
        // table builder instance untuk AbsenceApproval
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

        // table builder instance untuk AttendanceApproval
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

        // table builder instance untuk AttendanceQuotaApproval
        View::composer('dashboards.employee', function($view) {
            $attendanceQuotaTableBuilder = app('datatables.html.attendanceQuotaTable');
            $attendanceQuotaTable = $attendanceQuotaTableBuilder
                ->setTableAttribute('id', 'attendanceQuotaTable')
                ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
                ->addColumn(['data' => 'attendance_quota_id', 'name' => 'attendance_quota_id', 'title' => 'Pengajuan', 'orderable' => false])
                ->addColumn(['data' => 'attendance_quota.user.personnel_no', 'name' => 'attendance_quota.user.personnel_no', 'title' => 'Karyawan', 'orderable' => false,])
                ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Status', 'searchable' => false, 'orderable' => false])
                ->ajax(route('dashboards.attendance_quota_approval'));
            $view->with('attendanceQuotaTable', $attendanceQuotaTable);
        });        

        // table builder instance untuk TimeEventApproval
        View::composer('dashboards.employee', function($view) {
            $timeEventTableBuilder = app('datatables.html.timeEventTable');
            $timeEventTable = $timeEventTableBuilder
                ->setTableAttribute('id', 'timeEventTable')
                ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
                ->addColumn(['data' => 'time_event_id', 'name' => 'time_event_id', 'title' => 'Pengajuan', 'orderable' => false])
                ->addColumn(['data' => 'time_event.user.personnel_no', 'name' => 'time_event.user.personnel_no', 'title' => 'Karyawan', 'orderable' => false,])
                ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Status', 'searchable' => false, 'orderable' => false])
                ->ajax(route('dashboards.time_event_approval'));
            $view->with('timeEventTable', $timeEventTable);
        });        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // DataTables builder for absence approval
        $this->app->bind('datatables.html.absenceTable', function () {
            return $this->app->make(Builder::class);
        });

        // DataTables builder for attendance approval
        $this->app->bind('datatables.html.attendanceTable', function () {
            return $this->app->make(Builder::class);
        });        

        // DataTables builder for attendance quota approval
        $this->app->bind('datatables.html.attendanceQuotaTable', function () {
            return $this->app->make(Builder::class);

        });        
        // DataTables builder for time event approval
        $this->app->bind('datatables.html.timeEventTable', function () {
            return $this->app->make(Builder::class);
        });        
    }
}
