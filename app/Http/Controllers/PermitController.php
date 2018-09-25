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
        // ambil data izin dari attendances (elr) pada column alias
        $attendances = Attendance::where('personnel_no', Auth::user()->personnel_no)
            ->with(['permitType', 'stage', 'permitApprovals'])
            ->get();

        // ambil data izin dari absences (elr) (kecuali 0100 & 0200) pada column alias
        $absences = Absence::where('personnel_no', Auth::user()->personnel_no)
            ->excludeLeaves()
            ->with(['permitType', 'stage', 'permitApprovals'])
            ->get();

        // merge collection
        $permits = $attendances->merge($absences);

        // response untuk datatables attendances
        if ($request->ajax()) {

            return Datatables::of($permits)
                ->editColumn('summary', function ($permit) {                    
                    return view('permits._summary', [ 
                        'summary' => $permit,
                        'when' => $permit->created_at->format('d/m') 
                    ]);
                })
                ->editColumn('approver', function ($permit) {
                    // personnel_no dan name atasan
                    return view('layouts._personnel-no-with-name', [
                        'personnel_no' => $permit->permitApprovals->first()->user->personnel_no,
                        'employee_name' => $permit->permitApprovals->first()->user->name,
                    ]);
                })
                ->setRowAttr([
                    'data-href' => function($permit) {
                        if ($this->isInstanceOfAbsence($permit))
                            $routeName = 'permits.absence';
                        else if ($this->isInstanceOfAttendance($permit))
                            $routeName = 'permits.attendance';
                        return route($routeName, ['id' => $permit->id]);
                    }, 
                ])
                ->escapeColumns([0,1])
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'paging' => false,
            'searching' => false,
            'responsive' => [ 'details' => false ],
            "columnDefs" => [ [ "width" => "60%", "targets" => 0 ] ]
        ]);

        $html = $htmlBuilder
            ->addColumn([
                'data' => 'summary',
                'name' => 'summary',
                'title' => 'Summary',
                'searchable' => false,
                'orderable' => false, 
                ])
            ->addColumn([
                'data' => 'approver',
                'name' => 'approver',
                'title' => 'Approver',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
                ]);

        // tampilkan view index dengan tambahan script html DataTables
        return view('permits.index',compact('html', 'permits'));
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

        $path = $request->file('attachment')->store('permits');
        $requestData = $request->all();
        $requestData['attachment'] = $path;
        
        // memeriksa apakah permit_type adalah attendance atau absence
        $permitType = $request->input('permit_type');
        if ($this->isAnAbsence($permitType)) {

            $absence_type_id = AbsenceType::where('subtype', $permitType)->first()->id;

            // membuat pengajuan izin dengan menambahkan data personnel_no
            Absence::create($requestData
                + ['personnel_no' => Auth::user()->personnel_no,
                   'absence_type_id' => $absence_type_id, ]);
                
        } else if ($this->isAnAttendance($permitType)) {

            $attendance_type_id = AttendanceType::where('subtype', $permitType)->first()->id;
            // membuat pengajuan izin dengan menambahkan data personnel_no
            Attendance::create($requestData
                + ['personnel_no' => Auth::user()->personnel_no,
                   'attendance_type_id' => $attendance_type_id, ]);
                
        } else {

        }

        // kembali ke index permits
        return redirect()->route('permits.index');
    }

    public function showAbsence($id)
    {
        $permit = Absence::find($id)
            ->load(['permitType', 'stage', 'permitApprovals']);

        $permitId = 'absence-' . $permit->id;
        
        return view('permits._show-absence', compact('permit', 'permitId'));
    }
    
    public function showAttendance($id)
    {
        $permit = Attendance::find($id)
            ->load(['permitType', 'stage', 'permitApprovals']);

        $permitId = 'attendance-' . $permit->id;

        return view('permits._show-attendance', compact('permit', 'permitId'));
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

    protected function isInstanceOfAbsence($permit)
    {
        return $permit instanceOf Absence;
    }

    protected function isInstanceOfAttendance($permit)
    {
        return $permit instanceOf Attendance;
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
