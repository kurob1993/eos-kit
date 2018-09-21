<?php

namespace App\http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\AbsenceApproval;
use App\Models\AttendanceApproval;
use App\Models\AttendanceQuotaApproval;
use App\Models\TimeEventApproval;

class EmployeeDashboardComposer
{
    public function compose(View $view)
    {
        // Jumlah item notifikasi untuk absence
        $countLeaveApprovals = AbsenceApproval::where('regno', Auth::user()->personnel_no)
            ->waitedForApproval()->get();
        $view->with('countLeaveApprovals', count($countLeaveApprovals));

        // Jumlah item notifikasi untuk permit
        $countPermitApprovals = AttendanceApproval::where('regno', Auth::user()->personnel_no)
            ->waitedForApproval()->get();
        $view->with('countPermitApprovals', count($countPermitApprovals));

        // Jumlah item notifikasi untuk overtime
        $countOvertimeApprovals = AttendanceQuotaApproval::where('regno', Auth::user()->personnel_no)
            ->waitedForApproval()->get();
        $view->with('countOvertimeApprovals', count($countOvertimeApprovals));

        // Jumlah item notifikasi untuk time_event
        $countTimeEventApprovals = TimeEventApproval::where('regno', Auth::user()->personnel_no)
            ->waitedForApproval()->get();
        $view->with('countTimeEventApprovals', count($countTimeEventApprovals));

        // disable paging, searching, details button but enable responsive
        $tableParameters = [ 'paging' => false, 'searching' => false, 'responsive' => [ 'details' => false ], ];
        $summaryField = [ 'data' => 'summary', 'name' => 'summary', 'title' => 'Summary', 'searchable' => false, 'orderable' => false, ];
        $detailField = [ 'data' => 'detail', 'name' => 'detail', 'title' => 'Detail', 'class' => 'desktop', 'searchable' => false, 'orderable' => false, ];
        $approverField = [ 'data' => 'approver', 'name' => 'approver', 'title' => 'Approver', 'class' => 'desktop', 'searchable' => false, 'orderable' => false, ];

        // table builder untuk AbsenceApproval
        $absenceTableBuilder = app('datatables.html.absenceTable');
        $absenceTable = $absenceTableBuilder
            ->setTableAttribute('id', 'absenceTable')
            ->addColumn($summaryField)
            ->addColumn($detailField)
            ->addColumn($approverField)
            ->ajax(route('dashboards.absence_approval'));
        $absenceTable->parameters($tableParameters);
        $view->with('absenceTable', $absenceTable);

        // table builder untuk AttendanceApproval
        $attendanceTableBuilder = app('datatables.html.attendanceTable');
        $attendanceTable = $attendanceTableBuilder
            ->setTableAttribute('id', 'attendanceTable')
            ->addColumn($summaryField)
            ->addColumn($detailField)
            ->addColumn($approverField)
            ->ajax(route('dashboards.attendance_approval'));
        $attendanceTable->parameters($tableParameters);
        $view->with('attendanceTable', $attendanceTable);
        
        // table builder untuk AttendanceQuotaApproval
        $attendanceQuotaTableBuilder = app('datatables.html.attendanceQuotaTable');
        $attendanceQuotaTable = $attendanceQuotaTableBuilder
            ->setTableAttribute('id', 'attendanceQuotaTable')
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'attendance_quota_id', 'name' => 'attendance_quota_id', 'title' => 'Pengajuan', 'orderable' => false])
            ->addColumn(['data' => 'attendance_quota.user.personnel_no', 'name' => 'attendance_quota.user.personnel_no', 'title' => 'Karyawan', 'orderable' => false,])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Status', 'searchable' => false, 'orderable' => false])
            ->ajax(route('dashboards.attendance_quota_approval'));
        $view->with('attendanceQuotaTable', $attendanceQuotaTable);

        // table builder untuk TimeEventApproval
        $timeEventTableBuilder = app('datatables.html.timeEventTable');
        $timeEventTable = $timeEventTableBuilder
            ->setTableAttribute('id', 'timeEventTable')
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'time_event_id', 'name' => 'time_event_id', 'title' => 'Pengajuan', 'orderable' => false])
            ->addColumn(['data' => 'time_event.user.personnel_no', 'name' => 'time_event.user.personnel_no', 'title' => 'Karyawan', 'orderable' => false,])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Status', 'searchable' => false, 'orderable' => false])
            ->ajax(route('dashboards.time_event_approval'));
        $view->with('timeEventTable', $timeEventTable);        
    }
}
