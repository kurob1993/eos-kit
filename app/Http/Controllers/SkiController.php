<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Models\Ski;
use App\Models\SkiDetail;
use App\Models\SkiApproval;
use App\Models\OvertimeReason;
use App\Models\SkiPerilaku;
use App\Models\SAP\StructDisp;
use App\Models\SAP\OrgText;

class SkiController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        $allowed = Auth::user()->employee->allowedToSubmitSubordinateSki();

        if ($allowed) {
            $lembur = "Daftar Sasaran Kerja";
            $subordinates = Auth::user()->employee->subordinates();

            $personal_no = [];
            foreach ($subordinates as $subordinate) {
                array_push($personal_no,$subordinate->personnel_no);
            }
            array_push($personal_no, Auth::user()->personnel_no);
            $overtimes = Ski::whereIn('personnel_no',$personal_no)->get();
        } else {
            // ambil data cuti untuk user tersebut
            $lembur = "Daftar Sasaran Kerja Saya";
            $personal_no = Auth::user()->personnel_no;
            $overtimes = Ski::where('personnel_no',$personal_no)->get();
        }

        // response untuk datatables attendanceQuota
        if ($request->ajax()) {

            return Datatables::of($overtimes)
            ->editColumn('id', function($ski){
                return $ski->plain_id;
            })
            ->editColumn('personnel_no', function($ski){
                $nik = '<span class="label label-info">'.$ski->personnel_no.'</span>';
                return $nik.' '.$ski->user['name'];
            })
            ->editColumn('month', function (Ski $Ski) {
                return '<span class="label label-warning">'.
                            $Ski->month."/".$Ski->year
                        .'</span> ';
            })
            ->editColumn('stage', function (Ski $Ski) {
                return '<span class="label label-'.$Ski->stage->classDescription.'">'.
                            $Ski->stage->description
                        .'</span> ';
            })
            ->editColumn('perilaku', function (Ski $Ski) {        
                $nilai = 0;            
                foreach($Ski->skiDetail as $klp)
                {
                    if($klp->klp == "Perilaku")
                    {
                        $nilai +=$klp->nilai;
                    }
                };
                return $nilai;
            })
            ->editColumn('kinerja', function (Ski $Ski) {        
                $nilai = 0;            
                foreach($Ski->skiDetail as $klp)
                {
                    if($klp->klp == "Kinerja")
                    {
                        $nilai +=$klp->nilai;
                    }
                };
                return $nilai;
            })
                ->editColumn('approver', function ($overtime) {
                    // personnel_no dan name atasan
                    $views = '';
                    foreach ($overtime->skiApproval as $item) {
                        $views =  $views . view('layouts._personnel-no-with-name', [
                            'personnel_no' => $item->employee['personnel_no'],
                            'employee_name' => $item->employee['name'],
                        ]) . '<br />';
                    }
                    return $views;
                })
                ->setRowAttr([
                    // href untuk dipasang di setiap tr
                    'data-href' => function ($ski) {
                        return route('ski.show', ['ski' => $ski->id]);
                    }
                ])
                ->escapeColumns([0, 1])
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'paging' => true,
            'searching' => false,
            'sDom' => 'tpi',
            'responsive' => ['details' => false],
            "columnDefs" => [["width" => "10%", "targets" => 0]]
        ]);

        $html = $htmlBuilder
            ->addColumn([
                'data' => 'id',
                'name' => 'id',
                'title' => 'ID',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
                ])
            ->addColumn([
                'data' => 'personnel_no',
                'name' => 'personnel_no',
                'title' => 'Nama',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'month',
                'name' => 'month',
                'title' => 'Periode',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ])->addColumn([
                'data' => 'stage',
                'name' => 'stage',
                'title' => 'Tahapan',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ])->addColumn([
                'data' => 'perilaku',
                'name' => 'perilaku',
                'title' => 'Perilaku',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ])->addColumn([
                'data' => 'kinerja',
                'name' => 'kinerja',
                'title' => 'Kinerja',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ])->addColumn([
                'data' => 'approver',
                'name' => 'approver',
                'title' => 'Approver',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ]);

        // tampilkan view index dengan tambahan script html DataTables
        return view('ski.index')->with(compact('html', 'overtimes', 'lembur','allowed'));
    }

    public function create(Request $request)
    {
        $subordinate = $request->subordinate;
        
        // user yang dapat melakukan pengajuan lembur
        $user = Auth::user()->personnel_no;

        $golongan = Auth::user()->employee->esgrp;

        // data master prilaku
        $perilakus = SkiPerilaku::all();
        
        // mengecek apakah boleh mengajukan overtime untuk bawahan
        $allowed = Auth::user()
            ->employee
            ->allowedToSubmitSubordinateSki();

        if ($allowed && $subordinate) {
            // route untuk menyimpan from employee
            $formRoute = route('ski.store');
            $pageContainer = 'layouts.employee._page-container';

            // menampilkan view create overtime secretary
            return view(
                'ski.subordinate.create',
                compact('user', 'formRoute', 'pageContainer', 'perilakus')
            );
        } else {
            // route untuk menyimpan from employee
            $formRoute = route('ski.store');
            $pageContainer = 'layouts.employee._page-container';

            // menampilkan view create overtime secretary
            return view(
                'ski.create',
                compact('user', 'formRoute', 'pageContainer', 'perilakus','golongan')
            );
        }
    }

    public function store(Request $request)
    {  
        dd($request->input());

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
            $ski->dirnik = Auth::user()->personnel_no;
            $ski->save();

            $skiid = $ski->id;
        }
        else {
            $skiid = $dataski[0]->id;
        }

        $ski = Ski::where('personnel_no', $request->personnel_no)
            ->where('year', $request->tahun)
            ->where('month', $request->bulan)
            ->first();

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
                    return redirect()->route('ski.index');
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
                }
                
            }
        }

    }

    public function show($id)
    {
        $ski = Ski::find($id)
            ->load(['stage', 'skiApproval','skiDetail']);

        $skiId = $ski->id;

        return view('ski.show', compact('ski', 'skiId'));
    }

    public function edit($id)
    {
        $ski = Ski::find($id);
        $pageContainer = 'layouts.employee._page-container';
        return view('ski.edit', compact('pageContainer','ski'));
    }

    public function update(Request $request, $id)
    {
        $SkiApproval = ski::find($id)->skiApproval->pluck('regno')->toArray();
        if( !in_array(Auth::user()->personnel_no,$SkiApproval) ){
            Session::flash("flash_notification", [
                "level" => "danger",
                "message" => "Mohon Maaf anda tidak memiliki aksess untuk merubah data (ID: SKI-".$id.")"
            ]);
            return redirect()->route('dashboards.approval');
        }
        
        // dd($request->all());
        if(isset($request->klp)){
            foreach ($request->klp as $key => $value) {
                $SkiDetail = SkiDetail::find($key);
                $SkiDetail->klp = $value;
                $SkiDetail->save();
            }
            foreach ($request->sasaran as $key => $value) {
                $SkiDetail = SkiDetail::find($key);
                $SkiDetail->sasaran = $value;
                $SkiDetail->save();
            }
            foreach ($request->kode as $key => $value) {
                $SkiDetail = SkiDetail::find($key);
                $SkiDetail->kode = $value;
                $SkiDetail->save();
            }
            foreach ($request->ukuran as $key => $value) {
                $SkiDetail = SkiDetail::find($key);
                $SkiDetail->ukuran = $value;
                $SkiDetail->save();
            }
            foreach ($request->bobot as $key => $value) {
                $SkiDetail = SkiDetail::find($key);
                $SkiDetail->bobot = $value;
                $SkiDetail->save();
            }
            foreach ($request->skor as $key => $value) {
                $SkiDetail = SkiDetail::find($key);
                $SkiDetail->skor = $value;
                $SkiDetail->save();
            }
            foreach ($request->nilai as $key => $value) {
                $SkiDetail = SkiDetail::find($key);
                $SkiDetail->nilai = $value;
                $SkiDetail->save();
            }

            //hapus
            foreach ($request->klp as $key => $value) {
                if($value == null){
                    $SkiDetail = SkiDetail::destroy($key);
                }
            }
        }

        //add
        if(isset($request->add_klp)){
            foreach ($request->add_klp as $key => $value) {
                if ($value !== null) {
                    $SkiDetail = new SkiDetail();
                    $SkiDetail->ski_id = $id;
                    $SkiDetail->klp = $value;
                    $SkiDetail->sasaran = $request->add_sasaran[$key];
                    $SkiDetail->kode = $request->add_kode[$key];
                    $SkiDetail->ukuran = $request->add_ukuran[$key];
                    $SkiDetail->bobot = $request->add_bobot[$key];
                    $SkiDetail->skor = $request->add_skor[$key];
                    $SkiDetail->nilai = $request->add_nilai[$key];
                    $SkiDetail->save();
                }
            }
        }
        
        return redirect()->route('dashboards.approval');
    }

    public function destroy($id)
    {
        //
    }
}
