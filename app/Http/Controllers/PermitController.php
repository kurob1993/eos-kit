<?php

namespace App\Http\Controllers;

use Illuminate\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Session;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Models\Attendance;
use App\Models\Absence;

class PermitController extends Controller
{

    public function index(Request $request, Builder $htmlBuilder)
    {
        // response untuk datatables attendances
        if ($request->ajax()) {

            // ambil data izin dari attendances untuk user tersebut
            $attendances = Attendance::where('personnel_no', Auth::user()->personnel_no)
                ->with(['attendanceType', 'stage'])
                ->get();

            // ambil data izin dari absences (kecuali 0100 & 0200) untuk user tersebut
            $attendances = Absence::where('personnel_no', Auth::user()->personnel_no)
                ->excludeLeaves()
                ->with(['absenceType', 'stage'])
                ->get();

            // mengembalikan data sesuai dengan format yang dibutuhkan DataTables
            return Datatables::of($attendances)
                ->editColumn('stage.description', function (Absence $absence) {
                    return '<span class="label label-default">' 
                    . $absence->stage->description . '</span>';})
                ->editColumn('start_date', function (Absence $absence) {
                    return $absence->start_date->format(config('emss.date_format'));})
                ->editColumn('end_date', function (Absence $absence) {
                    return $absence->end_date->format(config('emss.date_format'));})
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
                'data' => 'absence_type.text', 
                'name' => 'absence_type.text', 
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
        return view('permits.index')->with(compact('html'));
    }

    public function create()
    {
        try {
            // mendapatkan data employee dari user
            // dan mengecek apakah dapat melakukan pelimpahan
            $canDelegate = Auth::user()->employee()->firstOrFail()->hasSubordinate();

        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa tidak ada data karyawan yang bisa ditemukan
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Tidak ditemukan data karyawan. Silahkan hubungi Divisi HCI&A."
            ]);
            // batalkan view create dan kembali ke parent
            return redirect()->route('permits.index');
        }

        // apakah ada yang belum selesai pengajuan cutinya?
        $incompletedAbsence = Absence::where('personnel_no', Auth::user()->personnel_no)
            ->incompleted()->get();
        $incompletedAttendance = Attendance::where('personnel_no', Auth::user()->personnel_no)
            ->incompleted()->get();
        if (sizeof($incompletedAbsence) > 0 || sizeof($incompletedAttendance > 0)) {
            Session::flash("flash_notification", [
                "level"   =>  "danger",
                "message"=>"Data pengajuan cuti sudah ada dan harus diselesaikan prosesnya " . 
                "sebelum mengajukan cuti kembali."
            ]);
            // batalkan view create dan kembali ke parent
            return redirect()->route('permits.index');       
        }        

        // tampilkan view create
        return view('permits.create', [
            'can_delegate' => $canDelegate,
            'absence_quota' => $absenceQuota
            ]
        );
    }

    public function store(Request $request)
    {
        //
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
