<?php

namespace App\Http\Controllers;

use App\Models\AbsenceApproval;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use Session;

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
        // response untuk datatables absences approval
        if ($request->ajax()) {

            // ambil data persetujuan absence, WARNING nested relationship eager loading
            $absenceApprovals = AbsenceApproval::where('regno', Auth::user()->personnel_no)
                ->with(['status:id,description', 'absence.user.employee']);

            // mengembalikan data sesuai dengan format yang dibutuhkan DataTables
            return Datatables::of($absenceApprovals)
                ->editColumn('absence_id', function (AbsenceApproval $absenceApproval) {
                    return '<address>' .
                    '<strong>'. $absenceApproval->absence->deduction .' hari cuti</strong><br>' .
                    $absenceApproval->absence->start_date->format(config('emss.date_format')) . 
                    ' - ' . $absenceApproval->absence->end_date->format(config('emss.date_format')) . '<br>' .
                    $absenceApproval->absence->personnel_no . ' - ' . $absenceApproval->absence->user->name .
                    '</address>';
                })
                ->addColumn('action', function (AbsenceApproval $absenceApproval) {
                    return view('dashboards._action', [
                        'model' => $absenceApproval,
                        'approve_url' => route('dashboards.approve', $absenceApproval->id),
                        'reject_url' => route('dashboards.reject', $absenceApproval->id),
                        'confirm_message' => "Yakin melakukan ",
                    ]);
                })
                ->escapeColumns([2])
                ->make(true);
        }

        // html builder untuk menampilkan kolom di datatables
        $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'absence_id', 'name' => 'absence_id', 'title' => 'absence_id'])
            ->addColumn(['data' => 'status.description', 'name' => 'status.description', 'title' => 'description'])
            ->addColumn(['data' => 'approved', 'name' => 'approved', 'title' => 'approved', 'searchable' => false])
            ->addColumn(['data' => 'action', 'name' => 'action', b'title' => 'action', 'searchable' => false])
            ->addColumn(['data' => 'text', 'name' => 'text', 'title' => 'text', 'searchable' => false]);

        // tampilkan view index dengan tambahan script html DataTables
        return view('dashboards.employee')->with(compact('html'));
    }

    public function personnelServiceDashboard()
    {
        return view('dashboards.personnel_service');
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
            "message" => "Berhasil menyetujui " . Status::rejectStatus()->id,
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
            "message" => "Berhasil menolak " . Status::rejectStatus()->id,
        ]);

        // kembali lagi ke dashboard employee
        return redirect()->route('dashboards.employee');
    }
}
