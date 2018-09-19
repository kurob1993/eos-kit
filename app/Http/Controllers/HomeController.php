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

    public function absenceApproval(Request $request)
    {
      // response untuk datatables absences approval
        if ($request->ajax()) {

            // ambil data persetujuan absence, WARNING nested relationship eager loading
            $absenceApprovals = AbsenceApproval::where('regno', Auth::user()->personnel_no)
                ->leavesOnly()
                ->with(['status:id,description', 'absence.user.employee', 'absence.absenceType']);

            // mengembalikan data sesuai dengan format yang dibutuhkan DataTables
            return Datatables::of($absenceApprovals)
                ->editColumn('absence_id', function (AbsenceApproval $a) {
                    return '<address>' .'<strong>'. 
                    $a->absence->deduction . ' hari cuti</strong><br>' .
                    $a->absence->absenceType->text . '<br>' .
                    $a->absence->formattedStartDate . ' - ' . 
                    $a->absence->formattedEndDate . '<br>' .
                    '</address>';
                })
                ->editColumn('absence.user.personnel_no', function (AbsenceApproval $a) {
                    return $a->absence->personnel_no . ' - ' . 
                    $a->absence->user->name . '<br>' . 
                    $a->absence->user->employee->position_name;
                })
                ->editColumn('action', function (AbsenceApproval $a) {
                    if ($a->isNotWaiting) {
                        return '<span class="label '. (($a->isApproved) ? 'label-primary' : 'label-danger') . '">' .
                        $a->status->description . '</span>' . '<br>' .
                        '<small>' . $a->updated_at . 
                        '</small><br><small>' . $a->text . '</small>';
                    } else {
                        return view('dashboards._action', [
                            'model' => $a,
                            'approve_url' => route('dashboards.approve', ['id' => $a->id, 'approval' => 'absence']),
                            'reject_url' => route('dashboards.reject', ['id' => $a->id, 'approval' => 'absence']),
                            'confirm_message' => "Yakin melakukan ",
                        ]);
                    }
                })
                ->orderColumn('id', '-id $1')
                ->escapeColumns([2])
                ->make(true);
        }
    }

    public function attendanceApproval(Request $request)
    {
      // response untuk datatables absences approval
        if ($request->ajax()) {

            // // ambil data persetujuan attendance, WARNING nested relationship eager loading
            $attendanceApprovals = AttendanceApproval::where('regno', Auth::user()->personnel_no)
                ->with(['status:id,description', 'attendance.user.employee', 'attendance.attendanceType']);

            // mengembalikan data sesuai dengan format yang dibutuhkan DataTables
            return Datatables::of($attendanceApprovals)
                ->editColumn('attendance_id', function (AttendanceApproval $a) {
                    return '<address>' .'<strong>'. 
                    $a->attendance->deduction . ' hari cuti</strong><br>' .
                    $a->attendance->attendanceType->text . '<br>' .
                    $a->attendance->formattedStartDate . ' - ' . 
                    $a->attendance->formattedEndDate . '<br>' .
                    '</address>';
                })
                ->editColumn('attendance.user.personnel_no', function (AttendanceApproval $a) {
                    return $a->attendance->personnel_no . ' - ' . 
                    $a->attendance->user->name . '<br>' . 
                    $a->attendance->user->employee->position_name;
                })
                ->editColumn('action', function (AttendanceApproval $a) {
                    if ($a->isNotWaiting) {
                        return '<span class="label '. (($a->isApproved) ? 'label-primary' : 'label-danger') . '">' .
                        $a->status->description . '</span>' . '<br>' .
                        '<small>' . $a->updated_at . 
                        '</small><br><small>' . $a->text . '</small>';
                    } else {
                        return view('dashboards._action', [
                            'model' => $a,
                            'approve_url' => route('dashboards.approve', ['id' => $a->id, 'approval' => 'attendance']),
                            'reject_url' => route('dashboards.reject', ['id' => $a->id, 'approval' => 'attendance']),
                            'confirm_message' => "Yakin melakukan ",
                        ]);
                    }
                })
                ->orderColumn('id', '-id $1')
                ->escapeColumns([2])
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
                ->editColumn('timeEvent_id', function (TimeEventApproval $a) {
                    return '<address>' .'<strong>'. 
                    $a->timeEvent->deduction . ' hari cuti</strong><br>' .
                    $a->timeEvent->timeEventType->text . '<br>' .
                    $a->timeEvent->formattedStartDate . ' - ' . 
                    $a->timeEvent->formattedEndDate . '<br>' .
                    '</address>';
                })
                ->editColumn('timeEvent.user.personnel_no', function (TimeEventApproval $a) {
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
                ->with(['status:id,description', 'attendanceQuota.user.employee', 'attendanceQuota.attendanceQuotaType']);

            // mengembalikan data sesuai dengan format yang dibutuhkan DataTables
            return Datatables::of($attendanceQuotaApprovals)
                ->editColumn('attendanceQuota_id', function (attendanceQuotaApproval $a) {
                    return '<address>' .'<strong>'. 
                    $a->attendanceQuota->deduction . ' hari cuti</strong><br>' .
                    $a->attendanceQuota->attendanceQuotaType->text . '<br>' .
                    $a->attendanceQuota->formattedStartDate . ' - ' . 
                    $a->attendanceQuota->formattedEndDate . '<br>' .
                    '</address>';
                })
                ->editColumn('attendanceQuota.user.personnel_no', function (attendanceQuotaApproval $a) {
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
        // tampilkan pesan bahwa telah berhasil menyetujui
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyetujui cuti."
        ]);
        
        // poor database design
        switch ($approval) {
            case 'absence': $approved = AbsenceApproval::find($id); break;
            case 'attendance': $approved = AttendanceApproval::find($id); break;
            case 'time_event': $approved = TimeEventApproval::find($id); break;
            case 'attendance_quota': $approved = AttendanceQuotaApproval::find($id); break;
        }

        if (!$approved->update($request->all() 
            + ['status_id' => Status::approveStatus()->id])) {
            // kembali lagi jika gagal
            return redirect()->back();
        }

        // kembali lagi ke dashboard employee
        return redirect()->route('dashboards.employee');
    }

    public function reject(Request $request, $approval, $id)
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
}
