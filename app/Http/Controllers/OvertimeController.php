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
        $allowed = Auth::user()
            ->employee
            ->allowedToSubmitSubordinateOvertime();

        if($allowed) {
            $lembur ="Daftar Lembur Bawahan Saya";
            $subordinates = Auth::user()
                ->employee
                ->foremanAndOperatorSubordinates();

            $overtimes = collect();
            foreach ($subordinates as $subordinate) {
                $overtimes = $overtimes->merge(
                    AttendanceQuota::where('personnel_no', $subordinate->personnel_no)
                        ->with(['overtimeReason', 'stage'])
                        ->get()
                );
            }
        } else {
            // ambil data cuti untuk user tersebut
            $lembur ="Daftar Lembur Saya";
            $overtimes = AttendanceQuota::ofLoggedUser()
                ->with(['overtimeReason', 'stage'])
                ->get();
        }

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
                        $views = '';
                        foreach ($overtime->attendanceQuotaApproval as $item) {
                            $views =  $views . view('layouts._personnel-no-with-name', [
                                'personnel_no' => $item->employee['personnel_no'],
                                'employee_name' => $item->employee['name'],
                            ]) . '<br />';
                        }
                    return $views;
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
        return view('overtimes.index')->with(compact('html', 'overtimes','lembur'));
    }

    public function create()
    {
        // user yang dapat melakukan pengajuan lembur
        $user = Auth::user()->personnel_no;

        // alasan lembur
        $overtime_reason = OvertimeReason::all('id', 'text')
        ->mapWithKeys(function ($item) {
            return [$item['id'] => $item['text']];
        })
        ->all();

        // mengecek apakah boleh mengajukan overtime untuk bawahan
        $allowed = Auth::user()
            ->employee
            ->allowedToSubmitSubordinateOvertime();

        if ($allowed) {
            // route untuk menyimpan from employee
            $formRoute = route('overtimes.store');
            $pageContainer = 'layouts.employee._page-container';
            
            // menampilkan view create overtime secretary
            return view('overtimes.createas', 
                compact('overtime_reason','user','formRoute','pageContainer')
            );
        } else {

            Session::flash("flash_notification", [
                "level" => "danger",
                "message" => "Mohon maaf, Anda tidak dapat mengajukan lembur. " . 
                    "Silahkan hubungi Supervisor/Superintendent/Sekretaris Divisi " .
                    "untuk mengajukan lembur Anda."
            ]);
            // batalkan view create dan kembali ke parent
            return redirect()->route('overtimes.index'); 
        }
    }

    public function store(StoreAttendanceQuotaRequest $request)
    {
        // tampilkan pesan bahwa telah berhasil mengajukan cuti
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan pengajuan lembur.",
        ]);
        
        // start date
        $start_date = Carbon::createFromFormat('Y-m-d H:i', 
            $request->input('start_date') . ' ' . $request->input('from'));
        // menghitung end_date berdasarkan day_assignment
        switch ($request->input('day_assignment')) {
            case '=':
                $end_date = Carbon::create(
                    $start_date->year, 
                    $start_date->month,
                    $start_date->day,
                    substr($request->input('to'), 0, 2),
                    substr($request->input('to'), 3, 2),
                    0);
            break;
            case '>':
                $end_date = Carbon::create(
                    $start_date->year, 
                    $start_date->month,
                    $start_date->day,
                    substr($request->input('to'), 0, 2),
                    substr($request->input('to'), 3, 2),
                    0)
                    ->addDays(1);
            break;
        }

        // membuat pengajuan lembur dengan menambahkan data personnel_no
        $overtime = new AttendanceQuota();
        $overtime->personnel_no = $request->input('personnel_no');
        $overtime->start_date = $start_date;
        $overtime->end_date = $end_date;
        $overtime->attendance_quota_type_id = AttendanceQuotaType::suratPerintahLembur()->id;
        $overtime->overtime_reason_id = $request->input('overtime_reason_id');
        $overtime->dirnik = Auth::user()->personnel_no;
        $overtime->save();

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
