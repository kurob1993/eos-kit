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
use App\Models\SkiPerilaku;
use App\Models\SkiDetail;
use App\Models\SAP\StructDisp;
use App\Models\SAP\OrgText;


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
        // data master prilaku
        $perilakus = SkiPerilaku::all();

        $formRoute = route('secretary.ski.store');
        $user = Auth::guard('secr')->user()->boss;
        $pageContainer = 'layouts.secretary._page-container';
        return view('ski.createas',
            compact('user', 'formRoute', 'pageContainer','perilakus')
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
        // $ski = new Ski();
        // $ski->personnel_no = $request->personnel_no;
        // $ski->month = $request->bulan;
        // $ski->year = $request->tahun;
        // $ski->stage_id = 1;
        // $ski->secretary_id = Auth::guard('secr')->user()->id;

        // if ($ski->save()) {
        //     foreach ($request->klp as $key => $value) {
        //         if ($value !== null) {
        //             $skid = new SkiDetail();
        //             $skid->ski_id = $ski->id;
        //             $skid->klp = $value;
        //             $skid->sasaran = $request->sasaran[$key];
        //             $skid->kode = $request->kode[$key];
        //             $skid->ukuran = $request->ukuran[$key];
        //             $skid->bobot = $request->bobot[$key];
        //             $skid->skor = $request->skor[$key];
        //             $skid->nilai = $request->nilai[$key];
        //             $skid->save();
        //         }
        //     }
        // }

        $disp = StructDisp::where('empnik',$request->personnel_no)
            ->selfStruct()
            ->get();

        $dispdata = $disp->transform(function ($item, $key) {
            return $item->minDivisiData();
        });

        if(isset($dispdata[0]['ObjectID'])){
            $objectid = $dispdata[0]['ObjectID'];
        }else {
            $objectid = 0;
        }

        if(isset($dispdata[0]['EndDate'])){
            $enddate = $dispdata[0]['EndDate'];
        }else {
            $enddate = 0;
        }

        if(isset($dispdata[0]['Objectname'])){
            $divisi = $dispdata[0]['Objectname'];
        }else {
            $divisi = $dispdata[0];
        }

        $dataski = Ski::where('personnel_no', $request->personnel_no)
            ->where('year', $request->tahun)
            ->where('month', $request->bulan)
            ->get();

        $cekski = $dataski->count();     

        if($cekski < 1) {
            $ski = new Ski();
            $ski->personnel_no = $request->personnel_no;
            $ski->month = $request->bulan;
            $ski->year = $request->tahun;
            $ski->object_id = $objectid;
            $ski->end_date = $enddate;
            $ski->divisi = $divisi;
            $ski->stage_id = 1;
            $ski->secretary_id = Auth::guard('secr')->user()->id;
            $ski->save();

            $skiid = $ski->id;
        }
        else {
            $skiid = $dataski[0]->id;
        }

        // perilaku
        if($request->input('aksi') ==  1)
        {
            if($ski != null)
            {
                $cekdataPerilaku =  SkiDetail::where('ski_id', $ski->id)
                    ->where('klp', "Perilaku")
                    ->get()
                    ->count();    
    
                if($cekdataPerilaku > 0)
                {
                    Session::flash("flash_notification", [
                        "level" => "danger",
                        "message" => "Tidak input perilaku Kerja Karyawan karena tanggal pengajuan "
                        . "sudah pernah diajukan sebelumnya (ID " . $ski->id . ": "
                        . $ski->month."-".$ski->year. ").",
                    ]);
                    return redirect()->route('ski.create');
                }
                else 
                {
                    // dd($request->klpp);
                    foreach ($request->klpp as $key => $value) {
                        //dd($request->all());
                        if ($value !== null) {
                            $skid = new SkiDetail();
                            $skid->ski_id = $skiid;
                            $skid->klp = $value;
                            $skid->sasaran = $request->sasaranp[$key];
                            $skid->kode = $request->kodep[$key];
                            $skid->ukuran = $request->ukuranp[$key];
                            $skid->bobot = $request->bobotp[$key];
                            $skid->skor = $request->skorp[$key];
                            $skid->nilai = $request->nilaip[$key];
                            $skid->save();
                        }
                    }

                    Session::flash("flash_notification", [
                        "level" => "success",
                        "message" => "Berhasil Input perilaku Kerja Karyawan.",
                    ]);

                    // // kembali ke halaman index ski
                    return redirect()->route('ski.create');
                }
            }
        }
        else 
        {
            if($ski != null)
            {
                $cekdataKinerja =  SkiDetail::where('ski_id', $ski->id)
                    ->where('klp', "Kinerja")
                    ->get()
                    ->count();    
    
                if($cekdataKinerja > 0)
                {
                    Session::flash("flash_notification", [
                        "level" => "danger",
                        "message" => "Tidak input Sasaran Kinerja Individu karena tanggal pengajuan "
                        . "sudah pernah diajukan sebelumnya (ID " . $ski->id . ": "
                        . $ski->month."-".$ski->year. ").",
                    ]);
                    return redirect()->route('ski.create');
                }
                else 
                {
                    // kinerja
                    foreach ($request->klp as $key => $value) {
                        if ($request->sasaran[$key] !== null) {
                            $skid = new SkiDetail();
                            $skid->ski_id = $skiid;
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
                    
                    Session::flash("flash_notification", [
                        "level" => "success",
                        "message" => "Berhasil Input Sasaran Kinerja Individu.",
                    ]);

                    // // kembali ke halaman index ski
                    return redirect()->route('ski.index');
                    // // kembali ke halaman index overtime
                    // return redirect()->route('secretary.ski.index');
                }
                
            }
        }
    }
}