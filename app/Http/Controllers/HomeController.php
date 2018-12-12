<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Models\AbsenceApproval;
use App\Models\AttendanceApproval;
use App\Models\TimeEventApproval;
use App\Models\AttendanceQuotaApproval;
use App\Models\Status;
use App\Models\Absence;
use App\Models\Attendance;
use App\Models\AttendanceQuota;
use App\Models\TimeEvent;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function noRole()
    {
        return view('layouts.no-role');
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        return $this->employeeDashboard($request, $htmlBuilder);
    }

    public function employeeDashboard($request, $htmlBuilder)
    {
        $subordinates = Auth::user()->employee->subordinates();
        $d = date('m');
        $y = date('Y');
        $dy = date('M Y');
        $chartTitle = "";
        $leaveChartTitle = "Pengajuan Cuti";
        $permitChartTitle = "Pengajuan Izin";
        $timeEventChartTitle = "Pengajuan Tidak Slash";
        $chartOptions = [
            "caption" => $chartTitle,
            "subcaption" => $dy,
            "xaxisname" => "NIK",
            "yaxisname" => "Durasi",
            "theme" => "fusion",
            "baseFont" => "Karla",
            "baseFontColor" => "#153957",
            "outCnvBaseFont" => "Karla",
        ];


        $leaveChartData = [];
        foreach ($subordinates as $subordinate) {
            if ($subordinate->leaves->count() > 0) {
                $absences = Absence::monthYearPeriodOf($d, $y, $subordinate->personnel_no) 
                    ->leavesOnly() 
                    ->get();

                $x = 0;
                foreach($absences as $absence) { $x += $absence->duration; }
                $labelValue = [ "label" => (string) $subordinate->personnel_no, "value" => $x ];
                array_push($leaveChartData, $labelValue);
            }
        }

        $permitChartData = [];
        foreach ($subordinates as $subordinate) {
            if ($subordinate->permits->count() > 0) {
                $absences = Absence::monthYearPeriodOf($d, $y, $subordinate->personnel_no) 
                    ->excludeLeaves() 
                    ->get();
                $x = 0;
                foreach($absences as $absence) { $x += $absence->duration; }
                $attendances = Attendance::monthYearPeriodOf($d, $y, $subordinate->personnel_no) 
                    ->get();
                foreach($attendances as $attendance) { $x += $attendance->duration; }
                $labelValue = [ "label" => (string) $subordinate->personnel_no, "value" => $x ];
                array_push($permitChartData, $labelValue);                
            }
        }

        $timeEventChartData = [];
        foreach ($subordinates as $subordinate) {
            if ($subordinate->timeEvents->count() > 0) {
                $timeEvents = TimeEvent::monthYearPeriodOf($d, $y, $subordinate->personnel_no);
                $labelValue = [ 
                    "label" => (string) $subordinate->personnel_no, 
                    "value" => $subordinate->timeEvents->count() 
                ];
                array_push($timeEventChartData, $labelValue);
            }
        }

        $chartTitle = $leaveChartTitle;
        $dataSource = [ "chart" => $chartOptions, "data" => $leaveChartData ];
        $leaveChart = new \FusionCharts(
            "column2d",
            "leaveChart" ,
            "100%",
            300,
            "leave-chart",
            "json",
            json_encode($dataSource)
        );

        $chartTitle = $permitChartTitle;
        $dataSource = [ "chart" => $chartOptions, "data" => $permitChartData ];
        $permitChart = new \FusionCharts(
            "column2d",
            "permitChart" ,
            "100%",
            300,
            "permit-chart",
            "json",
            json_encode($dataSource)
        );

        $chartTitle = $timeEventChartTitle;
        $dataSource = [ "chart" => $chartOptions, "data" => $timeEventChartData ];
        $timeEventChart = new \FusionCharts(
            "column2d",
            "timeEventChart" ,
            "100%",
            300,
            "time-event-chart",
            "json",
            json_encode($dataSource)
        );

        return view('dashboards.employee', compact(
            'leaveChart',
            'permitChart',
            'timeEventChart'
        ));
    }

    public function basisDashboard()
    {
        return view('dashboards.basis');
    }

    public function personnelServiceDashboard()
    {
        return view('dashboards.personnel_service');
    }

    public function leaveApproval(Request $request)
    {
        // ambil data persetujuan absence, WARNING nested relationship eager loading
        $leaveApprovals = AbsenceApproval::where('regno', Auth::user()->personnel_no)
            ->leavesOnly()
            ->with([
                'status:id,description',
                'absence.user:personnel_no,name',
                'absence.absenceType'
            ]);

        if (is_numeric($request->input('stage_id')))
            $leaveApprovals->whereStageId('absence', $request->input('stage_id'));

        $leaveApprovals = $leaveApprovals->get([
            'id',
            'regno',
            'absence_id',
            'status_id',
            'created_at',
            'updated_at'
        ]);

      // response untuk datatables absences approval
        if ($request->ajax()) {

            return Datatables::of($leaveApprovals)
                ->editColumn('summary', function ($leaveApproval) {
                    return view('dashboards.leaves._summary', [
                        'summary' => $leaveApproval,
                        'when' => $leaveApproval->created_at->format('d/m')
                    ]);
                })
                ->editColumn('detail', function ($leaveApproval) {
                    return view('dashboards.leaves._detail', [
                        'detail' => $leaveApproval,
                    ]);
                })
                ->editColumn('approver', function ($leaveApproval) {
                    return view('layouts._personnel-no-with-name', [
                        'personnel_no' => $leaveApproval->employee->personnel_no,
                        'employee_name' => $leaveApproval->employee->name,
                    ]);
                })
                ->setRowAttr([
                    'data-href' => function($leaveApproval) {
                        return route('dashboards.leave_summary', [
                            'id' => $leaveApproval->id,
                            ]
                        );
                    },
                ])
                ->escapeColumns([0,1])
                ->make(true);
        }
    }

    public function permitApproval(Request $request)
    {
        // ambil data persetujuan absence, WARNING nested relationship eager loading
        $absenceApprovals = AbsenceApproval::where('regno', Auth::user()->personnel_no)
            ->excludeLeaves()
            ->with([
                'status:id,description',
                'permit.user:personnel_no,name',
                'permit.permitType'
            ]);

        if (is_numeric($request->input('stage_id')))
            $absenceApprovals->whereStageId('permit', $request->input('stage_id'));

        $absenceApprovals = $absenceApprovals->get([
            'id',
            'regno',
            'absence_id',
            'status_id',
            'created_at',
            'updated_at'
        ]);

        // ambil data persetujuan attendance, WARNING nested relationship eager loading
        $attendanceApprovals = AttendanceApproval::where('regno', Auth::user()->personnel_no)
            ->with([
                'status:id,description',
                'permit.user:personnel_no,name',
                'permit.permitType'
            ]);

        if (is_numeric($request->input('stage_id')))
            $attendanceApprovals->whereStageId('permit', $request->input('stage_id'));

         $permitApprovals = $attendanceApprovals->get([
            'id',
            'regno',
            'attendance_id',
            'status_id',
            'created_at',
            'updated_at'
        ])->merge($absenceApprovals);

        // response untuk datatables absences approval
        if ($request->ajax()) {

            return Datatables::of($permitApprovals)
                ->editColumn('summary', function ($permitApproval) {
                    return view('dashboards.permits._summary', [
                        'summary' => $permitApproval,
                        'when' => $permitApproval->created_at->format('d/m')
                    ]);
                })
                ->editColumn('detail', function ($permitApproval) {
                    return view('dashboards.permits._detail', [
                        'detail' => $permitApproval,
                    ]);
                })
                ->editColumn('approver', function ($permitApproval) {
                    return view('layouts._personnel-no-with-name', [
                        'personnel_no' => $permitApproval->employee->personnel_no,
                        'employee_name' => $permitApproval->employee->name,
                    ]);
                })
                ->setRowAttr([
                    'data-href' => function($permitApproval) {
                        if ($permitApproval->permit instanceof Absence)
                            $approval = 'absence';
                        else if ($permitApproval->permit instanceof Attendance)
                            $approval = 'attendance';
                        return route('dashboards.permit_summary', [
                            'id' => $permitApproval->id,
                            'approval' => $approval
                            ]
                        );
                    },
                ])
                ->escapeColumns([0,1])
                ->make(true);
        }
    }

    public function timeEventApproval(Request $request)
    {

        // ambil data persetujuan timeEvent, WARNING nested relationship eager loading
        $timeEventApprovals = TimeEventApproval::where('regno', Auth::user()->personnel_no)
            ->with([
                'status:id,description',
                'timeEvent.user:personnel_no,name',
                'timeEvent.timeEventType'
        ]);

        if (is_numeric($request->input('stage_id')))
            $timeEventApprovals->whereStageId('timeEvent', $request->input('stage_id'));

        $timeEventApprovals = $timeEventApprovals->get([
            'id',
            'regno',
            'time_event_id',
            'status_id',
            'created_at',
            'updated_at'
        ]);

      // response untuk datatables absences approval
        if ($request->ajax()) {

            return Datatables::of($timeEventApprovals)
                ->editColumn('summary', function ($timeEventApproval) {
                    return view('dashboards.time_events._summary', [
                        'summary' => $timeEventApproval,
                        'when' => $timeEventApproval->created_at->format('d/m')
                    ]);
                })
                ->editColumn('detail', function ($timeEventApproval) {
                    return view('dashboards.time_events._detail', [
                        'detail' => $timeEventApproval,
                    ]);
                })
                ->editColumn('approver', function ($timeEventApproval) {
                    return view('layouts._personnel-no-with-name', [
                        'personnel_no' => $timeEventApproval->employee->personnel_no,
                        'employee_name' => $timeEventApproval->employee->name,
                    ]);
                })
                ->setRowAttr([
                    'data-href' => function($timeEventApproval) {
                        return route('dashboards.time_event_summary', [
                            'id' => $timeEventApproval->id,
                            ]
                        );
                    },
                ])
                ->escapeColumns([0,1])
                ->make(true);
        }
    }

    public function overtimeApproval(Request $request)
    {
        // // ambil data persetujuan attendanceQuota, WARNING nested relationship eager loading
        $overtimeApprovals = AttendanceQuotaApproval::where('regno', Auth::user()->personnel_no)
            ->with([
                'status:id,description',
                'attendanceQuota.user:personnel_no,name',
                'attendanceQuota.overtimeReason'
            ]);

        if (is_numeric($request->input('stage_id')))
            $overtimeApprovals->whereStageId('attendanceQuota', $request->input('stage_id'));

        $overtimeApprovals = $overtimeApprovals->get([
                'id',
                'regno',
                'attendance_quota_id',
                'status_id',
                'created_at',
                'updated_at'
            ]);

        // response untuk datatables absences approval
        if ($request->ajax()) {

            return Datatables::of($overtimeApprovals)
                ->editColumn('summary', function ($overtimeApproval) {
                    return view('dashboards.overtimes._summary', [
                        'summary' => $overtimeApproval,
                        'when' => $overtimeApproval->created_at->format('d/m')
                    ]);
                })
                ->editColumn('detail', function ($overtimeApproval) {
                    return view('dashboards.overtimes._detail', [
                        'detail' => $overtimeApproval,
                    ]);
                })
                ->editColumn('approver', function ($overtimeApproval) {
                    $approvals = $overtimeApproval->attendanceQuota->attendanceQuotaApproval;
                    $a = '';

                    foreach ($approvals as $approval) {
                        $a = $a . view('layouts._personnel-no-with-name', [
                            'personnel_no' => $approval->employee->personnel_no,
                            'employee_name' => $approval->employee->name,
                        ]) . '<br />';
                    }

                    return $a;
                })
                ->setRowAttr([
                    'data-href' => function($overtimeApproval) {
                        return route('dashboards.overtime_summary', [
                            'id' => $overtimeApproval->id,
                            ]
                        );
                    },
                ])
                ->escapeColumns([0,1])
                ->make(true);
        }
    }

    public function approve(Request $request, $approval, $id)
    {
        // poor database design
        switch ($approval) {
            case 'leave':
                $approved = AbsenceApproval::find($id);
                $moduleText = config('emss.modules.leaves.text');
            break;
            case 'absence':
                $approved = AbsenceApproval::find($id);
                $moduleText = config('emss.modules.permits.text');
            break;
            case 'attendance':
                $approved = AttendanceApproval::find($id);
                $moduleText = config('emss.modules.permits.text');
            break;
            case 'time_event':
                $approved = TimeEventApproval::find($id);
                $moduleText = config('emss.modules.time_events.text');
            break;
            case 'overtime':
                $approved = AttendanceQuotaApproval::find($id);
                $moduleText = config('emss.modules.overtimes.text');
            break;
        }

        $approved->status_id = Status::approveStatus()->id;
        if (!$approved->save()) {
            // kembali lagi jika gagal
            return redirect()->back();
        }

        // tampilkan pesan bahwa telah berhasil menyetujui
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyetujui " . $moduleText
        ]);

        // kembali lagi ke dashboard employee
        return redirect()->route('dashboards.employee');
    }

    public function reject(Request $request, $approval, $id)
    {
        // poor database design
        switch ($approval) {
            case 'leave':
                $approved = AbsenceApproval::find($id);
                $moduleText = config('emss.modules.leaves.text');
            break;
            case 'absence':
                $approved = AbsenceApproval::find($id);
                $moduleText = config('emss.modules.permits.text');
            break;
            case 'attendance':
                $approved = AttendanceApproval::find($id);
                $moduleText = config('emss.modules.permits.text');
            break;
            case 'time_event':
                $approved = TimeEventApproval::find($id);
                $moduleText = config('emss.modules.time_events.text');
            break;
            case 'overtime':
                $approved = AttendanceQuotaApproval::find($id);
                $moduleText = config('emss.modules.overtimes.text');
            break;
        }

        if (!$approved->update($request->all()
            + ['status_id' => Status::rejectStatus()->id])) {
            // kembali lagi jika gagal
            return redirect()->back();
        }

        // tampilkan pesan bahwa telah berhasil menolak
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menolak " . $moduleText
        ]);

        // kembali lagi ke dashboard employee
        return redirect()->route('dashboards.employee');
    }

    public function leaveSummary($id)
    {
        $leaveApproval = AbsenceApproval::find($id);

        return view('dashboards.leaves._modal', [
            'leave' => $leaveApproval->absence,
            'approve_url' => route('dashboards.approve', ['id' => $leaveApproval->id, 'approval' => 'leave']),
            'reject_url' => route('dashboards.reject', ['id' => $leaveApproval->id, 'approval' => 'leave']),
            'confirm_message' => "Yakin melakukan ",
            ]);
    }

    public function permitSummary($approval, $id)
    {
        // poor database design
        switch ($approval) {
            case 'absence':
                $approved = AbsenceApproval::find($id);
                $moduleText = config('emss.modules.absences.text');
            break;
            case 'attendance':
                $approved = AttendanceApproval::find($id);
                $moduleText = config('emss.modules.attendances.text');
            break;
        }

        return view('dashboards.permits._modal', [
            'permit' => $approved->permit,
            'approve_url' => route('dashboards.approve', ['id' => $approved->id, 'approval' => $approval]),
            'reject_url' => route('dashboards.reject', ['id' => $approved->id, 'approval' => $approval]),
            'confirm_message' => "Yakin melakukan ",
            ]);
    }

    public function timeEventSummary($id)
    {
        $timeEventApproval = TimeEventApproval::find($id);

        return view('dashboards.time_events._modal', [
            'timeEvent' => $timeEventApproval->timeEvent,
            'approve_url' => route('dashboards.approve', ['id' => $timeEventApproval->id, 'approval' => 'time_event']),
            'reject_url' => route('dashboards.reject', ['id' => $timeEventApproval->id, 'approval' => 'time_event']),
            'confirm_message' => "Yakin melakukan ",
            ]);
    }

    public function overtimeSummary($id)
    {
        $overtimeApproval = AttendanceQuotaApproval::find($id);

        return view('dashboards.overtimes._modal', [
            'overtime' => $overtimeApproval->attendanceQuota,
            'approve_url' => route('dashboards.approve', ['id' => $overtimeApproval->id, 'approval' => 'overtime']),
            'reject_url' => route('dashboards.reject', ['id' => $overtimeApproval->id, 'approval' => 'overtime']),
            'confirm_message' => "Yakin melakukan ",
            ]);
    }
}