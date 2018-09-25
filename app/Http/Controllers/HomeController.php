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

    public function index(Request $request, Builder $htmlBuilder)
    {
        // if (Laratrust::hasRole('basis')) return $this->basisDashboard();
        // if (Laratrust::hasRole('employee')) return $this->employeeDashboard();
        // if (Laratrust::hasRole('personnel_service')) return $this->personnelServiceDashboard();

        return $this->employeeDashboard($request, $htmlBuilder);
    }

    public function basisDashboard()
    {
        return view('dashboards.basis');
    }

    public function employeeDashboard($request, $htmlBuilder)
    {
        
        
        return view('dashboards.employee');
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
                ])
            ->get(['id', 'regno', 'absence_id', 'status_id', 'created_at', 'updated_at']);

      // response untuk datatables absences approval
        if ($request->ajax()) {

            return Datatables::of($leaveApprovals->sortBy('status.id')
            ->sortBy('absence.user.personnel_no'))
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
                        'personnel_no' => $leaveApproval->user->personnel_no,
                        'employee_name' => $leaveApproval->user->name,
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
                ])
            ->get(['id', 'regno', 'absence_id', 'status_id', 'created_at', 'updated_at']);

        // ambil data persetujuan attendance, WARNING nested relationship eager loading
        $attendanceApprovals = AttendanceApproval::where('regno', Auth::user()->personnel_no)
            ->with([
                'status:id,description', 
                'permit.user:personnel_no,name', 
                'permit.permitType'
                ])
            ->get(['id', 'regno', 'attendance_id', 'status_id', 'created_at', 'updated_at']);

        // merge collection
        $permitApprovals = $attendanceApprovals->merge($absenceApprovals);            

        // response untuk datatables absences approval
        if ($request->ajax()) {

            return Datatables::of($permitApprovals->sortBy('permit.user.personnel_no')
            ->sortBy('status.id'))
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
                        'personnel_no' => $permitApproval->user->personnel_no,
                        'employee_name' => $permitApproval->user->name,
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
                ])
            ->get(['id', 'regno', 'time_event_id', 'status_id', 'created_at', 'updated_at']);

      // response untuk datatables absences approval
        if ($request->ajax()) {

            return Datatables::of($timeEventApprovals->sortBy('status.id')
            ->sortBy('timeEvent.user.personnel_no'))
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
                        'personnel_no' => $timeEventApproval->user->personnel_no,
                        'employee_name' => $timeEventApproval->user->name,
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
                ])
                ->get(['id', 'regno', 'attendance_quota_id', 'status_id', 'created_at', 'updated_at']);

        // response untuk datatables absences approval
        if ($request->ajax()) {

            return Datatables::of($overtimeApprovals->sortBy('status.id')
            ->sortBy('attendanceQuota.user.personnel_no'))
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
                    return view('layouts._personnel-no-with-name', [
                        'personnel_no' => $overtimeApproval->user->personnel_no,
                        'employee_name' => $overtimeApproval->user->name,
                    ]);
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
                $moduleText = config('emss.leaves.text');
            break;
            case 'absence': 
                $approved = AbsenceApproval::find($id);
                $moduleText = config('emss.permits.text');
            break;
            case 'attendance': 
                $approved = AttendanceApproval::find($id);
                $moduleText = config('emss.permits.text');
            break;
            case 'time_event': 
                $approved = TimeEventApproval::find($id);
                $moduleText = config('emss.time_events.text');
            break;
            case 'overtime': 
                $approved = AttendanceQuotaApproval::find($id);
                $moduleText = config('emss.overtimes.text'); 
            break;
        }

        if (!$approved->update($request->all() 
            + ['status_id' => Status::approveStatus()->id])) {
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
                $moduleText = config('emss.leaves.text');
            break;
            case 'absence': 
                $approved = AbsenceApproval::find($id);
                $moduleText = config('emss.permits.text');
            break;
            case 'attendance': 
                $approved = AttendanceApproval::find($id);
                $moduleText = config('emss.permits.text');
            break;
            case 'time_event': 
                $approved = TimeEventApproval::find($id);
                $moduleText = config('emss.time_events.text');
            break;
            case 'overtime': 
                $approved = AttendanceQuotaApproval::find($id);
                $moduleText = config('emss.overtimes.text'); 
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
                $moduleText = config('emss.absences.text');
            break;
            case 'attendance': 
                $approved = AttendanceApproval::find($id);
                $moduleText = config('emss.attendances.text');
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
