<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\DataTables\SecOvertimeDataTable;
use App\DataTables\SecSkiDataTable;
use App\Http\Requests\StoreAttendanceQuotaRequest;
use App\Models\AttendanceQuota;
use App\Models\AttendanceQuotaType;
use App\Models\OvertimeReason;
use App\Models\Ski;
use App\Models\SkiDetail;


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

    public function ski(SecSkiDataTable $dataTable)
    {
        return $dataTable->render('secretary.ski.index');
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

    public function createSki()
    {
        $formRoute = route('secretary.ski.store');
        $user = Auth::guard('secr')->user()->boss;
        $pageContainer = 'layouts.secretary._page-container';
        return view('ski.createas',
            compact('user', 'formRoute', 'pageContainer')
        );
    }

    public function storeOvertime(StoreAttendanceQuotaRequest $request)
    {
        // tampilkan pesan bahwa telah berhasil mengajukan cuti
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan pengajuan lembur.",
        ]);
                
        // start date
        $start_date = Carbon::createFromFormat('Y-m-d H:i', 
        $request->input('start_date') . ' ' . $request->input('from'));

        $end_date = Carbon::create(
            $start_date->year, 
            $start_date->month,
            $start_date->day,
            substr($request->input('to'), 0, 2),
            substr($request->input('to'), 3, 2),
            0);   

        // menghitung end_date berdasarkan day_assignment
        switch ($request->input('day_assignment')) {
            case '>':
                $end_date->addDays(1);
            break;
        }

        // membuat pengajuan lembur dengan menambahkan data personnel_no
        $overtime = new AttendanceQuota();
        $overtime->personnel_no = $request->input('personnel_no');
        $overtime->start_date = $start_date;
        $overtime->end_date = $end_date;
        $overtime->attendance_quota_type_id = AttendanceQuotaType::suratPerintahLembur()->id;
        $overtime->overtime_reason_id = $request->input('overtime_reason_id');
        $overtime->secretary_id = Auth::guard('secr')->user()->id;
        $overtime->save();

        // kembali ke halaman index overtime
        return redirect()->route('secretary.overtimes.index');
    }

    public function storeSki(Request $request)
    {
        $ski = new Ski();
        $ski->personnel_no = $request->personnel_no;
        $ski->month = $request->bulan;
        $ski->year = $request->tahun;
        $ski->perilaku = $request->perilkau;
        $ski->stage_id = 1;
        $ski->secretary_id = Auth::guard('secr')->user()->id;

        if ($ski->save()) {
            foreach ($request->klp as $key => $value) {
                if ($value !== null) {
                    $skid = new SkiDetail();
                    $skid->ski_id = $ski->id;
                    $skid->klp = $value;
                    $skid->sasaran = $request->sasaran[$key];
                    $skid->kode = $request->kode[$key];
                    $skid->ukuran = $request->ukuran[$key];
                    $skid->bobot = $request->bobot[$key];
                    $skid->skor = $request->skor[$key];
                    $skid->nilai = $request->nilai[$key];
                    $skid->save();
                }
            }
        }

        // // kembali ke halaman index overtime
        return redirect()->route('secretary.ski.index');
    }
}