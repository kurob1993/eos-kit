<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Http\Requests\StoreAbsenceRequest;
use App\Models\Absence;
use App\Models\AbsenceQuota;

class LeaveController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        // response untuk datatables absences
        if ($request->ajax()) {

            // ambil data cuti untuk user tersebut
            $absences = Absence::where('personnel_no', Auth::user()->personnel_no)
                ->LeavesOnly()
                ->with(['absenceType', 'stage']);

            // mengembalikan data sesuai dengan format yang dibutuhkan DataTables
            return Datatables::of($absences)
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
        return view('leaves.index')->with(compact('html'));
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
            return redirect()->route('leaves.index');
        }

        try {
             // mendapatkan absence quota berdasarkan user
            $absenceQuota = AbsenceQuota::activeAbsenceQuota(Auth::user()->personnel_no)
            ->with('absenceType:id,text')->firstOrFail();

        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa tidak ada absence quota
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Belum ada kuota cuti untuk periode saat ini. " . 
                    "Silahkan hubungi Divisi HCI&A."
            ]);
            // batalkan view create dan kembali ke parent
            return redirect()->route('leaves.index');
        }

        // apakah ada yang belum selesai pengajuan cutinya?
        $incompleted = Absence::where('personnel_no', Auth::user()->personnel_no)
            ->incompleted()->get();
        if (sizeof($incompleted) > 0) {
            Session::flash("flash_notification", [
                "level"   =>  "danger",
                "message"=>"Data pengajuan cuti/izin sudah ada dan harus diselesaikan prosesnya " . 
                "sebelum mengajukan cuti kembali."
            ]);
            // batalkan view create dan kembali ke parent
            return redirect()->route('leaves.index');       
        }        

        // tampilkan view create
        return view('leaves.create', [
            'can_delegate' => $canDelegate,
            'absence_quota' => $absenceQuota
            ]
        );
    }

    public function store(StoreAbsenceRequest $request)
    {
        // tampilkan pesan bahwa telah berhasil mengajukan cuti
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan pengajuan cuti.",
        ]);

        // membuat pengajuan cuti dengan menambahkan data personnel_no
        $absence = Absence::create($request->all()
             + ['personnel_no' => Auth::user()->personnel_no]);

        return redirect()->route('leaves.index');
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
