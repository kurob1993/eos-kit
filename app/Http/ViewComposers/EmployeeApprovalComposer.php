<?php

namespace App\http\ViewComposers;

use Illuminate\View\View;
use App\Models\AbsenceApproval;
use App\Models\AttendanceApproval;
use App\Models\AttendanceQuotaApproval;
use App\Models\TimeEventApproval;
use App\Models\SkiApproval;
use App\Models\Stage;

class EmployeeApprovalComposer
{
    public function compose(View $view)
    {
        $view->with('stages', Stage::all());

        // Jumlah item notifikasi untuk absence
        $countLeaveApprovals = AbsenceApproval::ofLoggedUser()
            ->leavesOnly()
            ->waitedForApproval()
            ->get();
        $view->with('countLeaveApprovals', count($countLeaveApprovals));

        // Jumlah item notifikasi untuk permit
        $absenceApprovals = AbsenceApproval::ofLoggedUser()
            ->excludeLeaves()
            ->waitedForApproval()
            ->get();
        $attendanceApprovals = AttendanceApproval::ofLoggedUser()
            ->waitedForApproval()
            ->get();
        $view->with('countPermitApprovals', count($absenceApprovals) + count($attendanceApprovals));

        // Jumlah item notifikasi untuk overtime
        $countOvertimeApprovals = AttendanceQuotaApproval::ofLoggedUser()
            ->waitedForApproval()->get();
        $view->with('countOvertimeApprovals', count($countOvertimeApprovals));

        // Jumlah item notifikasi untuk Sasaran Kinerja Individu
        $countSki = SkiApproval::ofLoggedUser()
            ->waitedForApproval()->get();
        $view->with('countSki', count($countSki));

        // Jumlah item notifikasi untuk time_event
        $countTimeEventApprovals = TimeEventApproval::ofLoggedUser()
            ->waitedForApproval()->get();
        $view->with('countTimeEventApprovals', count($countTimeEventApprovals));

        // disable paging, searching, details button but enable responsive
        $tableParameters = [
            'sDom' => '<"toolbar">tpi',
            'pageLength' => 20,
            'paging' => true,
            'searching' => false,
            'responsive' => true,
            'autoWidth' => false,
            'columnDefs' => [
                // NIK, DURASI, AKSI
                [ 'responsivePriority' => 1, 'width'=> '15%' , 'targets' => 0 ],
                [ 'responsivePriority' => 2, 'targets' => -3 ],
                [ 'responsivePriority' => 3, 'targets' => -1 ]
            ]
        ];

        $leaveFields = [
            [
                'data' => 'absence.id',
                'name' => 'absence.id',
                'title' => 'ID',
                'orderable' => false,
            ],
            [
                'data' => 'absence.personnel_no',
                'name' => 'absence.personnel_no',
                'title' => 'NIK',
                'orderable' => false,
            ],
            [
                'data' => 'absence.user.name',
                'name' => 'absence.user.name',
                'title' => 'Nama',
                'orderable' => false,
            ],
            [
                'data' => 'absence.start_date',
                'name' => 'absence.start_date',
                'title' => 'Mulai',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'absence.end_date',
                'name' => 'absence.end_date',
                'title' => 'Berakhir',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'absence.note',
                'name' => 'absence.note',
                'title' => 'Catatan',
                'class' => 'none',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'duration',
                'name' => 'duration',
                'title' => 'Durasi',
                'class' => 'text-center',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'absence.address',
                'name' => 'absence.address',
                'title' => 'Alamat',
                'class' => 'none',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'absence.absence_type.text',
                'name' => 'absence.absence_type.text',
                'title' => 'Jenis Cuti',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'absence.absence_approvals',
                'name' => 'absence.absence_approvals',
                'title' => 'Approval',
                'searchable' => false,
                'class' => 'none',
                'orderable' => false,
            ],
            [
                'data' => 'action',
                'name' => 'action',
                'title' => 'Aksi',
                'class' => 'text-center',
                'searchable' => false,
                'orderable' => false,
            ],
        ];

        $permitFields = [
            [
                'data' => 'permit.id',
                'name' => 'permit.id',
                'title' => 'ID',
                'orderable' => false,
            ],
            [
                'data' => 'permit.personnel_no',
                'name' => 'permit.personnel_no',
                'title' => 'NIK',
                'orderable' => false,
            ],
            [
                'data' => 'permit.user.name',
                'name' => 'permit.user.name',
                'title' => 'Nama',
                'orderable' => false,
            ],
            [
                'data' => 'permit.start_date',
                'name' => 'permit.start_date',
                'title' => 'Mulai',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'permit.end_date',
                'name' => 'permit.end_date',
                'title' => 'Berakhir',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'permit.note',
                'name' => 'permit.note',
                'title' => 'Catatan',
                'class' => 'none',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'duration',
                'name' => 'duration',
                'title' => 'Durasi',
                'class' => 'text-center',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'permit.permit_type.text',
                'name' => 'permit.permit_type.text',
                'title' => 'Jenis Izin',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'permit.permit_approvals',
                'name' => 'permit.permit_approvals',
                'title' => 'Approval',
                'searchable' => false,
                'class' => 'none',
                'orderable' => false,
            ],
            [
                'data' => 'action',
                'name' => 'action',
                'title' => 'Aksi',
                'class' => 'text-center',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'permit.attachment',
                'name' => 'permit.attachment',
                'title' => 'Lampiran',
                'searchable' => false,
                'class' => 'none',
                'orderable' => false,
            ]
        ];

        $timeEventFields = [
            [
                'data' => 'time_event.id',
                'name' => 'time_event.id',
                'title' => 'ID',
                'orderable' => false,
            ],
            [
                'data' => 'time_event.personnel_no',
                'name' => 'time_event.personnel_no',
                'title' => 'NIK',
                'orderable' => false,
            ],
            [
                'data' => 'time_event.user.name',
                'name' => 'time_event.user.name',
                'title' => 'Nama',
                'orderable' => false,
            ],
            [
                'data' => 'time_event.check_date',
                'name' => 'time_event.check_date',
                'title' => 'Check Date',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'time_event.check_time',
                'name' => 'time_event.check_time',
                'title' => 'Check Time',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'time_event.time_event_type.description',
                'name' => 'time_event.time_event_type.description',
                'title' => 'Type',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'time_event.note',
                'name' => 'time_event.note',
                'title' => 'Catatan',
                'searchable' => false,
                'orderable' => false,
            ],
            [
                'data' => 'time_event.time_event_approvals',
                'name' => 'time_event.time_event_approvals',
                'title' => 'Approval',
                'searchable' => false,
                'class' => 'none',
                'orderable' => false,
            ],
            [
                'data' => 'action',
                'name' => 'action',
                'title' => 'Aksi',
                'class' => 'text-center',
                'searchable' => false,
                'orderable' => false,
            ],
        ];

        $overtimeFields = [
            [ 
                'data' => 'attendance_quota.id', 
                'name' => 'attendance_quota.id', 
                'title' => 'ID',
                'orderable' => false,
            ],
            [ 
                'data' => 'attendance_quota.personnel_no', 
                'name' => 'attendance_quota.personnel_no', 
                'title' => 'NIK',
                'orderable' => false,
            ],
            [ 
                'data' => 'attendance_quota.user.name', 
                'name' => 'attendance_quota.user.name', 
                'title' => 'Nama',
                'orderable' => false,
            ],
            [ 
                'data' => 'attendance_quota.start_date', 
                'name' => 'attendance_quota.start_date', 
                'title' => 'Tanggal Mulai',
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'attendance_quota.end_date', 
                'name' => 'attendance_quota.end_date', 
                'title' => 'Berakhir',
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'duration', 
                'name' => 'duration', 
                'title' => 'Durasi', 
                'class' => 'text-center',
                'searchable' => false,
                'orderable' => false,
            ],            
            [ 
                'data' => 'attendance_quota.overtime_reason.text', 
                'name' => 'attendance_quota.overtime_reason.text', 
                'title' => 'Alasan lembur', 
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'attendance_quota.attendance_quota_approval', 
                'name' => 'attendance_quota.attendance_quota_approval', 
                'title' => 'Approval', 
                'searchable' => false, 
                'class' => 'none',
                'orderable' => false,
            ],
            [ 
                'data' => 'action', 
                'name' => 'action', 
                'title' => 'Aksi', 
                'class' => 'text-center',
                'searchable' => false,
                'orderable' => false,
            ],
        ];

        $skiFields = [
            [ 
                'data' => 'ski.id', 
                'name' => 'ski.id', 
                'title' => 'ID',
                'orderable' => false,
            ],
            [ 
                'data' => 'ski.personnel_no', 
                'name' => 'ski.personnel_no', 
                'title' => 'NIK',
                'orderable' => false,
            ],
            [ 
                'data' => 'ski.user.name', 
                'name' => 'ski.user.name', 
                'title' => 'Nama',
                'orderable' => false,
            ],
            [ 
                'data' => 'ski.month', 
                'name' => 'ski.month', 
                'title' => 'Periode',
                'orderable' => false,
            ],
            [ 
                'data' => 'ski.perilaku', 
                'name' => 'ski.perilaku', 
                'title' => 'Perilaku',
                'orderable' => false,
            ],
            [ 
                'data' => 'ski.kinerja', 
                'name' => 'ski.kinerja', 
                'title' => 'Kinerja',
                'orderable' => false,
            ],
            [ 
                'data' => 'ski.ski_approval', 
                'name' => 'ski.ski_approval', 
                'title' => 'Approval', 
                'searchable' => false, 
                'class' => 'none',
                'orderable' => false,
            ],
            [ 
                'data' => 'action', 
                'name' => 'action', 
                'title' => 'Aksi', 
                'class' => 'text-center',
                'searchable' => false,
                'orderable' => false,
            ],
            [ 
                'data' => 'detail', 
                'name' => 'detail', 
                'title' => 'Detail', 
                'class' => 'text-center',
                'searchable' => false,
                'orderable' => false,
            ],
        ];

        // table builder untuk AbsenceApproval
        $leaveTableBuilder = app('datatables.html.leaveTable');
        $leaveTable = $leaveTableBuilder
            ->setTableAttribute('id', 'leaveTable')
            ->columns($leaveFields)
            ->minifiedAjax(
                route('dashboards.leave_approval'),
                'data.stage_id = $("#filter-leaveTable option:selected").val();', [ ]
            );
        $leaveTable->parameters($tableParameters);
        $view->with('leaveTable', $leaveTable);

        // table builder untuk AttendanceApproval
        $permitTableBuilder = app('datatables.html.permitTable');
        $permitTable = $permitTableBuilder
            ->setTableAttribute('id', 'permitTable')
            ->columns($permitFields)
            ->minifiedAjax(
                route('dashboards.permit_approval'),
                'data.stage_id = $("#filter-permitTable option:selected").val();', [ ]
            );
        $permitTable->parameters($tableParameters);
        $view->with('permitTable', $permitTable);

        // table builder untuk TimeEventApproval
        $timeEventTableBuilder = app('datatables.html.timeEventTable');
        $timeEventTable = $timeEventTableBuilder
            ->setTableAttribute('id', 'timeEventTable')
            ->columns($timeEventFields)
            ->minifiedAjax(
                route('dashboards.time_event_approval'),
                'data.stage_id = $("#filter-timeEventTable option:selected").val();', [ ]
            );
        $timeEventTable->parameters($tableParameters);
        $view->with('timeEventTable', $timeEventTable);

        // table builder untuk AttendanceQuotaApproval
        $overtimeTableBuilder = app('datatables.html.overtimeTable');
        $overtimeTable = $overtimeTableBuilder
            ->setTableAttribute('id', 'overtimeTable')
            ->columns($overtimeFields)
            ->minifiedAjax(
                route('dashboards.overtime_approval'),
                'data.stage_id = $("#filter-overtimeTable option:selected").val();', [ ]
            );
        $overtimeTable->parameters($tableParameters);
        $view->with('overtimeTable', $overtimeTable);

        // table builder untuk SkiApproval
        $skiTableBuilder = app('datatables.html.skiTable');
        $skiTable = $skiTableBuilder
            ->setTableAttribute('id', 'skiTable')
            ->columns($skiFields)
            ->minifiedAjax(
                route('dashboards.ski_approval'),
                'data.stage_id = $("#filter-skiTable option:selected").val();', [ ]
            );
        $skiTable->parameters($tableParameters);
        $view->with('skiTable', $skiTable);

        $tableNames = collect([
            [ 'tableName' => 'leaveTable', 'approval' => 'leave' ],
            [ 'tableName' => 'permitTable', 'approval' => 'permit' ],
            [ 'tableName' => 'overtimeTable', 'approval' => 'overtime' ],
            [ 'tableName' => 'skiTable', 'approval' => 'ski' ],
            [ 'tableName' => 'timeEventTable', 'approval' => 'time_event' ]
        ]);
        $view->with(compact('tableNames'));
    }
}