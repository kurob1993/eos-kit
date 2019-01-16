<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DataTables\SecOvertimeDataTable;
use App\Http\Requests\StoreAttendanceQuotaRequest;
use App\Models\AttendanceQuota;
use App\Models\AttendanceQuotaType;
use App\Models\OvertimeReason;



class SecretaryController extends Controller
{
    public function index()
    {
        return view('dashboards.secretary');
    }

    public function overtime(SecOvertimeDataTable $dataTable)
    {
        return $dataTable->render('secretary.overtimes.index');
    }

    public function travel(SecOvertimeDataTable $dataTable)
    {
        return $dataTable->render('secretary.travels.index');
    }

    public function createOvertime()
    {
        $formRoute = route('secretary.overtimes.store');
        $user = Auth::guard('secr')->user()->boss;
        $overtime_reason = OvertimeReason::all('id', 'text')
            ->mapWithKeys(function ($item) {
                return [$item['id'] => $item['text']];
            })
            ->all();
        $pageContainer = 'layouts.secretary._page-container';
        
        return view('overtimes.createas', 
            compact('user', 'overtime_reason', 'formRoute', 'pageContainer')
        );
    }

    public function createTravel()
    {
        return view('secretary.travels.create');
    }

    public function storeOvertime(StoreAttendanceQuotaRequest $request)
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
             + [ 'secretary_id' => Auth::guard('secr')->user()->id,
                 'end_date' => $end_date,
                 'attendance_quota_type_id' => AttendanceQuotaType::suratPerintahLembur()->id
        ]);

        // kembali ke halaman index overtime
        return redirect()->route('secretary.overtimes.index');
    }
}