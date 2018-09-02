<?php

namespace App\Http\Controllers;

use Session;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Models\Attendance;
use App\Models\AttendanceType;
use App\Models\Absence;
use App\Models\AbsenceType;

class PTEController extends Controller
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
        return view('ptes.index')->with(compact('html'));
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
            return redirect()->route('ptes.index');
        }

        // mencari data pengajuan absence yang masih belum selesai
        $incompletedAbsence = Absence::where('personnel_no', Auth::user()->personnel_no)
            ->incompleted()->get();
        
        // mencari data pengajuan attendance yang masih belum selesai
        $incompletedAttendance = Attendance::where('personnel_no', Auth::user()->personnel_no)
            ->incompleted()->get();
                
        // apakah ada yang belum selesai pengajuan cuti/izinnya??
        if (sizeof($incompletedAbsence) > 0 || sizeof($incompletedAttendance) > 0) {
            Session::flash("flash_notification", [
                "level"   =>  "danger",
                "message"=>"Data pengajuan cuti/izin sudah ada dan harus diselesaikan prosesnya " . 
                "sebelum mengajukan cuti kembali."
            ]);
            // batalkan view create dan kembali ke parent
            return redirect()->route('ptes.index');       
        }

        // transform array to key value pairs. For alternative:
        // $data = array_map(function($obj){ return (array) $obj; }, $ref);
        $absenceType = AbsenceType::excludeLeaves()
            ->get(['subtype', 'text'])
            ->mapWithKeys(function ($item) {
                return [$item['subtype'] => $item['text']];
            })
            ->all();
        $attendanceType = AttendanceType::all('subtype', 'text')
            ->mapWithKeys(function ($item) {
                return [$item['subtype'] => $item['text']];
            })
            ->all();
        
        // merge absence_types & attendance_types
        $permitTypes = array_merge($absenceType, $attendanceType);

        // tampilkan view create
        return view('ptes.create', [ 
            'can_delegate' => $canDelegate, 
            'permit_types' => $permitTypes,
        ]);
    }

    public function store(Request $request)
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
            return redirect()->route('ptes.create');
        }

        // permits form elements
        // personnel_no, start_date, end_date, deduction,
        // permit_type, attachment, note, delegation (if have subordinates)
        $validator = Validator::make($request->all(), [
            'personnel_no' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'deduction' => 'required',
            'permit_type' => 'required',
            'attachment' => 'required',
            'note' => 'required',
        ]);
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
}
