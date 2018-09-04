<?php

namespace App\Http\Controllers;

use Illuminate\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Session;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Models\AttendanceQuota;
use App\Models\AttendanceQuotaType;
use App\Models\OvertimeReason;

class OvertimeController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        // response untuk datatables absences
        if ($request->ajax()) {

            // ambil data cuti untuk user tersebut
            $attendanceQuotas = AttendanceQuota::where('personnel_no', Auth::user()->personnel_no)
                ->with(['overtimeReason', 'stage']);

            // mengembalikan data sesuai dengan format yang dibutuhkan DataTables
            return Datatables::of($attendanceQuotas)
                ->editColumn('stage.description', function (AttendanceQuota $attendanceQuota) {
                    return '<span class="label label-default">' 
                    . $attendanceQuota->stage->description . '</span>';})
                ->editColumn('start_date', function (AttendanceQuota $attendanceQuota) {
                    return $attendanceQuota->start_date->format(config('emss.date_format'));})
                ->editColumn('end_date', function (AttendanceQuota $attendanceQuota) {
                    return $attendanceQuota->end_date->format(config('emss.date_format'));})
                ->escapeColumns([4])
                ->make(true);
        }

        // html builder untuk menampilkan kolom di datatables
        $html = $htmlBuilder
            ->addColumn([
                'data' => 'id',
                'name' => 'id', 
                'title' => 'ID'
                ])
            ->addColumn([
                'data' => 'start_date', 
                'name' => 'start_date', 
                'title' => 'Mulai'
                ])
            ->addColumn([
                'data' => 'end_date', 
                'name' => 'end_date', 
                'title' => 'Berakhir'
                ])
            ->addColumn([
                'data' => 'overtimeReason.text', 
                'name' => 'overtimeReason.text', 
                'title' => 'Jenis', 
                'searchable' => false
                ])
            ->addColumn([
                'data' => 'stage.description', 
                'name' => 'stage.description', 
                'title' => 'Tahap', 
                'searchable' => false
                ]);

        // tampilkan view index dengan tambahan script html DataTables
        return view('overtimes.index')->with(compact('html'));
    }

    public function create()
    {
        $overtimeReason = OvertimeReason::all('id', 'text')
            ->mapWithKeys(function ($item) {
                return [$item['id'] => $item['text']];
            })
            ->all();

        // tampilkan view create
        return view('overtimes.create', [ 'overtime_reason' => $overtimeReason ]);
    }

    public function store(StoreAttendanceQuotaRequest $request)
    {
        // tampilkan pesan bahwa telah berhasil mengajukan cuti
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan pengajuan cuti.",
        ]);

        // membuat pengajuan lembur dengan menambahkan data personnel_no
        $absence = AttendanceQuota::create($request->all()
             + ['personnel_no' => Auth::user()->personnel_no]);

        return redirect()->route('overtimes.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
