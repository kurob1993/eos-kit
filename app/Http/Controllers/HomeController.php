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
                    return $leaveApproval
                        ->user
                        ->personnelNoWithName;
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
                'permit.user.employee', 
                'permit.permitType'
                ])
            ->get(['id', 'regno', 'attendance_id', 'status_id', 'created_at', 'updated_at']);

        // merge collection
        $permitApprovals = $attendanceApprovals->merge($absenceApprovals);            

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
                    return $permitApproval
                        ->user
                        ->personnelNoWithName;
                })
                ->setRowAttr([
                    'data-href' => function($permitApproval) {
                        if ($permitApproval->permit instanceof Absence)
                            $approval = 'absence';
                        else if ($permitApproval->permit instanceof Attendance)
                            $approval = 'attendance';
                        return route('dashboards.permit_summary', [
                            'id' => $permitApproval->permit->id,
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
      // response untuk datatables absences approval
        if ($request->ajax()) {

            // // ambil data persetujuan timeEvent, WARNING nested relationship eager loading
            $timeEventApprovals = TimeEventApproval::where('regno', Auth::user()->personnel_no)
                ->with(['status:id,description', 'timeEvent.user.employee', 'timeEvent.timeEventType']);

            // mengembalikan data sesuai dengan format yang dibutuhkan DataTables
            return Datatables::of($timeEventApprovals)
                ->editColumn('time_event_id', function (TimeEventApproval $a) {
                    return '<address>' .'<strong>'. 
                    'Izin tidak slash </strong>' .
                    $a->timeEvent->timeEventType->text . '<br>' .
                    $a->timeEvent->check_date . ' - ' . 
                    $a->timeEvent->check_time . '<br>' .
                    '</address>';
                })
                ->editColumn('time_event.user.personnel_no', function (TimeEventApproval $a) {
                    return $a->timeEvent->personnel_no . ' - ' . 
                    $a->timeEvent->user->name . '<br>' . 
                    $a->timeEvent->user->employee->position_name;
                })
                ->editColumn('action', function (TimeEventApproval $a) {
                    if ($a->isNotWaiting) {
                        return '<span class="label '. (($a->isApproved) ? 'label-primary' : 'label-danger') . '">' .
                        $a->status->description . '</span>' . '<br>' .
                        '<small>' . $a->updated_at . 
                        '</small><br><small>' . $a->text . '</small>';
                    } else {
                        return view('dashboards._action', [
                            'model' => $a,
                            'approve_url' => route('dashboards.approve', ['id' => $a->id, 'approval' => 'time_event']),
                            'reject_url' => route('dashboards.reject', ['id' => $a->id, 'approval' => 'time_event']),
                            'confirm_message' => "Yakin melakukan ",
                        ]);
                    }
                })
                ->orderColumn('id', '-id $1')
                ->escapeColumns([2])
                ->make(true);
        }
    }

    public function attendanceQuotaApproval(Request $request)
    {
      // response untuk datatables absences approval
        if ($request->ajax()) {

            // // ambil data persetujuan attendanceQuota, WARNING nested relationship eager loading
            $attendanceQuotaApprovals = AttendanceQuotaApproval::where('regno', Auth::user()->personnel_no)
                ->with(['status:id,description', 'attendanceQuota.user.employee', 'attendanceQuota.overtimeReason']);

            // mengembalikan data sesuai dengan format yang dibutuhkan DataTables
            return Datatables::of($attendanceQuotaApprovals)
                ->editColumn('attendance_quota_id', function (attendanceQuotaApproval $a) {
                    return '<address>' .
                    $a->attendanceQuota->overtimeReason->text . '<br>' .
                    $a->attendanceQuota->formattedStartDate . ' - ' . 
                    $a->attendanceQuota->formattedEndDate . '<br>' .
                    '</address>';
                })
                ->editColumn('attendance_quota.user.personnel_no', function (attendanceQuotaApproval $a) {
                    return $a->attendanceQuota->personnel_no . ' - ' . 
                    $a->attendanceQuota->user->name . '<br>' . 
                    $a->attendanceQuota->user->employee->position_name;
                })
                ->editColumn('action', function (attendanceQuotaApproval $a) {
                    if ($a->isNotWaiting) {
                        return '<span class="label '. (($a->isApproved) ? 'label-primary' : 'label-danger') . '">' .
                        $a->status->description . '</span>' . '<br>' .
                        '<small>' . $a->updated_at . 
                        '</small><br><small>' . $a->text . '</small>';
                    } else {
                        return view('dashboards._action', [
                            'model' => $a,
                            'approve_url' => route('dashboards.approve', ['id' => $a->id, 'approval' => 'attendance_quota']),
                            'reject_url' => route('dashboards.reject', ['id' => $a->id, 'approval' => 'attendance_quota']),
                            'confirm_message' => "Yakin melakukan ",
                        ]);
                    }
                })
                ->orderColumn('id', '-id $1')
                ->escapeColumns([2])
                ->make(true);
        }
    }

    public function approve(Request $request, $approval, $id)
    {
        // poor database design
        switch ($approval) {
            case 'leave': 
                $approved = AbsenceApproval::find($id); 
                $moduleText = config('emss.absences.text');
            break;
            case 'permit': 
                $approved = AttendanceApproval::find($id);
                $moduleText = config('emss.attendances.text');
            break;
            case 'time_event': 
                $approved = TimeEventApproval::find($id);
                $moduleText = config('emss.time_events.text');
            break;
            case 'attendance_quota': 
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
                $moduleText = config('emss.absences.text');
            break;
            case 'permit': 
                $approved = AttendanceApproval::find($id);
                $moduleText = config('emss.attendances.text');
            break;
            case 'time_event': 
                $approved = TimeEventApproval::find($id);
                $moduleText = config('emss.time_events.text');
            break;
            case 'attendance_quota': 
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

        $leaveApprovalId = $leaveApproval->id;
        
        return view('dashboards.leaves._modal', [
            'leave' => $leaveApproval->absence, 
            'leaveId' => $leaveApprovalId,
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

        $approved = AttendanceApproval::find($id);

        $approvedId = $approval . '-' . $approved->id;
        
        return view('dashboards.permits._modal', [
            'permit' => $approved->permit, 
            'permitId' => $approvedId,
            'approve_url' => route('dashboards.approve', ['id' => $approved->id, 'approval' => 'permit']),
            'reject_url' => route('dashboards.reject', ['id' => $approved->id, 'approval' => 'permit']),
            'confirm_message' => "Yakin melakukan ",
            ]);
    }
}
