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
    // // ambil data persetujuan absence, WARNING nested relationship eager loading
    // $absenceApprovals = AbsenceApproval::where('regno', Auth::user()->personnel_no)
    //     ->with(['status:id,description', 'absence.user.employee', 'absence.absenceType']);

    // // // ambil data persetujuan attendance, WARNING nested relationship eager loading
    // $attendanceApprovals = AttendanceApproval::where('regno', Auth::user()->personnel_no)
    //     ->with(['status:id,description', 'attendance.user.employee', 'attendance.attendanceType']);

    // // ambil data persetujuan timeEvent, WARNING nested relationship eager loading
    // $timeEventApprovals = TimeEventApproval::where('regno', Auth::user()->personnel_no)
    //     ->with(['status:id,description', 'timeEvent.user.employee', 'timeEvent.timeEventType']);

    // // ambil data persetujuan attendanceQuota, WARNING nested relationship eager loading
    // $attendanceQuotaApprovals = AttendanceQuotaApproval::where('regno', Auth::user()->personnel_no)
    //     ->with(['status:id,description', 'attendanceQuota.user.employee', 'attendanceQuota.attendanceQuotaType']);

    // echo($absenceApprovals->get(['id', 'status_id', 'absence_id'])->toJson());
    // var_dump($attendanceApprovals->get()->toArray());
    // var_dump($timeEventApprovals->get()->toArray());
    // var_dump($attendanceQuotaApprovals->get()->toArray());

    // exit(1);

        // // html builder untuk menampilkan kolom di datatables
        // $html = $htmlBuilder
        //     ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
        //     ->addColumn(['data' => 'absence_id', 'name' => 'absence_id', 'title' => 'Pengajuan', 'orderable' => false])
        //     ->addColumn(['data' => 'absence.user.personnel_no', 'name' => 'absence.user.personnel_no', 'title' => 'Karyawan', 'orderable' => false,])
        //     ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Status', 'searchable' => false, 'orderable' => false]);


        // tampilkan view index dengan tambahan script html DataTables
        // return view('dashboards.employee')->with(compact('html'));
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
                ->with(['status:id,description', 'absence.user.employee', 'absence.absenceType'])
                ->get();

            // // // ambil data persetujuan attendance, WARNING nested relationship eager loading
            // $attendanceApprovals = AttendanceApproval::where('regno', Auth::user()->personnel_no)
            //     ->with(['status:id,description', 'attendance.user.employee', 'attendance.attendanceType']);

            // // ambil data persetujuan timeEvent, WARNING nested relationship eager loading
            // $timeEventApprovals = TimeEventApproval::where('regno', Auth::user()->personnel_no)
            //     ->with(['status:id,description', 'timeEvent.user.employee', 'timeEvent.timeEventType']);

            // // ambil data persetujuan attendanceQuota, WARNING nested relationship eager loading
            // $attendanceQuotaApprovals = AttendanceQuotaApproval::where('regno', Auth::user()->personnel_no)
            //     ->with(['status:id,description', 'attendanceQuota.user.employee', 'attendanceQuota.attendanceQuotaType']);

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
                            'approve_url' => route('dashboards.approve', $a->id),
                            'reject_url' => route('dashboards.reject', $a->id),
                            'confirm_message' => "Yakin melakukan ",
                        ]);
                    }
                })
                // ->orderColumn('id', '-id $1')
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
                ->editColumn('attendance_id', function (AbsenceApproval $a) {
                    return '<address>' .'<strong>'. 
                    $a->attendance->deduction . ' hari cuti</strong><br>' .
                    $a->attendance->attendanceType->text . '<br>' .
                    $a->attendance->formattedStartDate . ' - ' . 
                    $a->attendance->formattedEndDate . '<br>' .
                    '</address>';
                })
                ->editColumn('attendance.user.personnel_no', function (AbsenceApproval $a) {
                    return $a->attendance->personnel_no . ' - ' . 
                    $a->attendance->user->name . '<br>' . 
                    $a->attendance->user->employee->position_name;
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
                            'approve_url' => route('dashboards.approve', $a->id),
                            'reject_url' => route('dashboards.reject', $a->id),
                            'confirm_message' => "Yakin melakukan ",
                        ]);
                    }
                })
                // ->orderColumn('id', '-id $1')
                ->escapeColumns([2])
                ->make(true);
        }
    }

    public function approve(Request $request, $id)
    {
        // cari berdasarkan id kemudian update berdasarkan request + status approve
        $absenceApproval = AbsenceApproval::find($id);

        if (!$absenceApproval->update($request->all() 
            + ['status_id' => Status::approveStatus()->id])) {
            // kembali lagi jika gagal
            return redirect()->back();
        }

        // tampilkan pesan bahwa telah berhasil menyetujui
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyetujui cuti."
        ]);

        // kembali lagi ke dashboard employee
        return redirect()->route('dashboards.employee');
    }

    public function reject(Request $request, $id)
    {
        // cari berdasarkan id kemudian update berdasarkan request + status reject
        $absenceApproval = AbsenceApproval::find($id);

        if (!$absenceApproval->update($request->all() 
            + ['status_id' => Status::rejectStatus()->id])) {
            // kembali lagi jika gagal
            return redirect()->back();
        }

        // tampilkan pesan bahwa telah berhasil menolak
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menolak cuti" 
        ]);

        // kembali lagi ke dashboard employee
        return redirect()->route('dashboards.employee');
    }
}
