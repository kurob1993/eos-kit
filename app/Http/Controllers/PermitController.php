<?php

namespace App\Http\Controllers;

use Session;
use Validator;
use Illuminate\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Models\Attendance;
use App\Models\AttendanceType;
use App\Models\Absence;
use App\Models\AbsenceType;
use App\Http\Requests\StorePermitRequest;

class PermitController extends Controller
{

    public function index(Request $request, Builder $htmlBuilder)
    {
        // response untuk datatables attendances
        if ($request->ajax()) {

            // ambil data izin dari attendances (elr) pada column alias
            $attendances = Attendance::where('personnel_no', Auth::user()->personnel_no)
                ->with(['permitType', 'stage'])
                ->get();

            // ambil data izin dari absences (elr) (kecuali 0100 & 0200) pada column alias
            $absences = Absence::where('personnel_no', Auth::user()->personnel_no)
                ->excludeLeaves()
                ->with(['permitType', 'stage'])
                ->get();

            // merge collection
            $permits = $attendances->merge($absences);

            // mengembalikan data sesuai dengan format yang dibutuhkan DataTables
            return Datatables::of($permits)
                ->editColumn('stage.description', function ($a) {
                    return '<span class="label label-default">' 
                    . $a->stage->description . '</span>';})
                ->editColumn('start_date', function ($a) {
                    return $a->start_date->format(config('emss.date_format'));})
                ->editColumn('end_date', function ($a) {
                    return $a->end_date->format(config('emss.date_format'));})
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
                'data' => 'permit_type.text', 
                'name' => 'permit_type.text', 
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
            $canDelegate = Auth::user()->employee()->firstOrFail()->canDelegate();

        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa tidak ada data karyawan yang bisa ditemukan
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Tidak ditemukan data karyawan. Silahkan hubungi Divisi HCI&A."
            ]);
            // batalkan view create dan kembali ke parent
            return redirect()->route('permits.index');
        }

        // transform array to key value pairs. For alternative:
        // $data = array_map(function($obj){ return (array) $obj; }, $ref);
        $absenceType = $this->nonLeaveAbsenceTypes()
            ->mapWithKeys(function ($item) {
                $maxDuration = (!is_null($item['max_duration'])) ? 
                ' (' . $item['max_duration'] . ' hari)' : '';
                return [$item['subtype'] => $item['text'] . $maxDuration];
            })
            ->all();
        $attendanceType = $this->attendanceTypes()
            ->mapWithKeys(function ($item) {
                return [$item['subtype'] => $item['text']];
            })
            ->all();

        // tampilkan view create
        return view('permits.create', [ 
            'can_delegate' => $canDelegate, 
            'permit_types' => array_merge($absenceType, $attendanceType),
        ]);
    }

    public function store(StorePermitRequest $request)
    {
        try {
            // mendapatkan data employee dari user
            // dan mengecek apakah dapat melakukan pelimpahan
            $canDelegate = Auth::user()->employee()->firstOrFail()->canDelegate();

        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa tidak ada data karyawan yang bisa ditemukan
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Tidak ditemukan data karyawan. Silahkan hubungi Divisi HCI&A."
            ]);
            // batalkan view create dan kembali ke parent
            return redirect()->route('permits.create');
        }

        // tampilkan pesan bahwa telah berhasil mengajukan izin
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan pengajuan izin.",
        ]);

        // memeriksa apakah permit_type adalah attendance atau absence
        $permitType = $request->input('permit_type');
        if ($this->isAnAbsence($permitType)) {

            $absence_type_id = AbsenceType::where('subtype', $permitType)->first()->id;

            // membuat pengajuan izin dengan menambahkan data personnel_no
            Absence::create($request->all()
                + ['personnel_no' => Auth::user()->personnel_no,
                   'absence_type_id' => $absence_type_id, ]);
                
        } else if ($this->isAnAttendance($permitType)) {

            $attendance_type_id = AttendanceType::where('subtype', $permitType)->first()->id;
            // membuat pengajuan izin dengan menambahkan data personnel_no
            Attendance::create($request->all()
                + ['personnel_no' => Auth::user()->personnel_no,
                   'attendance_type_id' => $attendance_type_id, ]);
                
        } else {

        }

        // kembali ke index permits
        return redirect()->route('permits.index');
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

    protected function attendanceTypes()
    {
        return AttendanceType::all('subtype', 'text');
    }

    protected function nonLeaveAbsenceTypes()
    {
        return AbsenceType::excludeLeaves()
            ->get(['subtype', 'text', 'max_duration']);
    }

    protected function isAnAbsence($permitType)
    {
        $isAnAbsence = $this->nonLeaveAbsenceTypes()
            ->filter(function ($item, $key) use($permitType) {
                return $item->subtype == $permitType;
        });

        return !$isAnAbsence->isEmpty();
    }

    protected function isAnAttendance($permitType)
    {
        $isAnAttendance = $this->attendanceTypes()
            ->filter(function ($item, $key) use($permitType) {
                return $item->subtype == $permitType;
        });

        return !$isAnAttendance->isEmpty();
    }
}
