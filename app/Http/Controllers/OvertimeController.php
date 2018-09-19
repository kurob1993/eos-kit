<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Models\AttendanceQuota;
use App\Models\AttendanceQuotaType;
use App\Models\OvertimeReason;
use App\Http\Requests\StoreAttendanceQuotaRequest;

class OvertimeController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        // ambil data cuti untuk user tersebut
        $overtimes = AttendanceQuota::where('personnel_no', Auth::user()->personnel_no)
            ->with(['overtimeReason', 'stage'])
            ->get();

        // response untuk datatables attendanceQuota
        if ($request->ajax()) {

            return Datatables::of($overtimes)
                ->editColumn('summary', function ($overtime) {
                    // kolom summary menggunakan view _summary
                    return view('overtimes._summary', [ 
                        'summary' => $overtime,
                        'when' => $overtime->created_at->format('d/m') 
                    ]);
                })
                ->editColumn('approver', function ($overtime) {
                    // personnel_no dan name atasan
                    return $overtime
                        ->attendanceQuotaApprovals;
                })
                ->setRowAttr([
                    // href untuk dipasang di setiap tr
                    'data-href' => function ($overtime) {
                        return route('overtimes.show', ['leaf' => $overtime->id]);
                    } 
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
        return view('overtimes.index')->with(compact('html', 'overtimes'));
    }

    public function create()
    {
        // mengecek apakah boleh mengajukan overtime
        $allowedForOvertime = Auth::user()->employee->allowedForOvertime();

        // apakah tidak boleh mengajukan overtime?
        if (!$allowedForOvertime) {
            Session::flash("flash_notification", [
                "level"   =>  "danger",
                "message"=>"Mohon maaf, Anda tidak dapat mengajukan lembur " . 
                "karena golongan Anda bukan ES/EF/F."
            ]);
            // batalkan view create dan kembali ke parent
            return redirect()->route('overtimes.index');            
        }
        
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
            "message" => "Berhasil menyimpan pengajuan lembur.",
        ]);

        // menghitung end_date berdasarkan day_assignment
        switch ($request->input('day_assignment')) {
            case '=':
                $end_date = $request->input('start_date');
            break;
            case '>':
                $end_date = Carbon::parse($request->input('start_date'))->addDays(1);
            break;
        }

        // membuat pengajuan lembur dengan menambahkan data personnel_no
        $absence = AttendanceQuota::create($request->all()
             + ['personnel_no' => Auth::user()->personnel_no,
                'end_date' => $end_date,
                'attendance_quota_type_id' => AttendanceQuotaType::suratPerintahLembur()->id
                ]);

        // kembali ke halaman index overtime
        return redirect()->route('overtimes.index');
    }

    public function show($id)
    {
        $overtime = AttendanceQuota::find($id)
            ->load(['attendanceQuotaType', 'stage', 'attendanceQuotaApproval']);

        $overtimeId = $overtime->id;
        
        return view('overtimes.show', compact('overtime', 'overtimeId'));
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
