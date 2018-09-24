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
            ->leavesOnly()
            ->waitedForApproval()
            ->get();
        $view->with('countLeaveApprovals', count($countLeaveApprovals));

        // Jumlah item notifikasi untuk permit
        $absenceApprovals = AbsenceApproval::where('regno', Auth::user()->personnel_no)
            ->excludeLeaves()
            ->waitedForApproval()
            ->get();
        $attendanceApprovals = AttendanceApproval::where('regno', Auth::user()->personnel_no)
            ->waitedForApproval()
            ->get();
        $view->with('countPermitApprovals', count($absenceApprovals) + count($attendanceApprovals));

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
        $leaveTableBuilder = app('datatables.html.leaveTable');
        $leaveTable = $leaveTableBuilder
            ->setTableAttribute('id', 'leaveTable')
            ->addColumn($summaryField)
            ->addColumn($detailField)
            ->addColumn($approverField)
            ->ajax(route('dashboards.leave_approval'));
        $leaveTable->parameters($tableParameters);
        $view->with('leaveTable', $leaveTable);

        // table builder untuk AttendanceApproval
        $permitTableBuilder = app('datatables.html.permitTable');
        $permitTable = $permitTableBuilder
            ->setTableAttribute('id', 'permitTable')
            ->addColumn($summaryField)
            ->addColumn($detailField)
            ->addColumn($approverField)
            ->ajax(route('dashboards.permit_approval'));
        $permitTable->parameters($tableParameters);
        $view->with('permitTable', $permitTable);

        // table builder untuk TimeEventApproval
        $timeEventTableBuilder = app('datatables.html.timeEventTable');
        $timeEventTable = $timeEventTableBuilder
            ->setTableAttribute('id', 'timeEventTable')
            ->addColumn($summaryField)
            ->addColumn($detailField)
            ->addColumn($approverField)
            ->ajax(route('dashboards.time_event_approval'));
        $timeEventTable->parameters($tableParameters);
        $view->with('timeEventTable', $timeEventTable);

        // table builder untuk AttendanceQuotaApproval
        $overtimeTableBuilder = app('datatables.html.overtimeTable');
        $overtimeTable = $overtimeTableBuilder
            ->setTableAttribute('id', 'overtimeTable')
            ->addColumn($summaryField)
            ->addColumn($detailField)
            ->addColumn($approverField)
            ->ajax(route('dashboards.overtime_approval'));
        $overtimeTable->parameters($tableParameters);
        $view->with('overtimeTable', $overtimeTable);
    }
}
