<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Models\Ski;
use App\Models\Stage;
use App\Models\SkiDetail;
use App\Models\SkiApproval;
use App\Models\SAP\StructDisp;

class SkiController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        
        $skiApproval = SkiApproval::where('regno',Auth::user()->employee->personnel_no)->get();
        $idSki = $skiApproval->map(function($item,$key){
            return $item->ski_id;
        });

        $stage = isset($request->search['value']) ? $request->search['value'] : '';
        $skiBawahan = Ski::whereIn('id',$idSki)->where('stage_id','like','%'.$stage.'%')->with(['skiApproval'])->get();
        
        $personal_no = Auth::user()->personnel_no;
        $ski = Ski::where('personnel_no',$personal_no)->where('stage_id','like','%'.$stage.'%')->with(['skiApproval'])->get();
        
        $ski = $ski->merge($skiBawahan);
        $ski = $ski->values()->all();
        // return $ski;
        // response untuk datatables attendanceQuota
        if ($request->ajax()) {

            return Datatables::of($ski)
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
            })->editColumn('approver', function ($ski) {
                // personnel_no dan name atasan
                $views = '';
                foreach ($ski->skiApproval as $item) {
                    $approve = $item->status_id;
                    $approve = $approve == 1 ? 'default' : 'primary';
                    $html = '<span class="label label-'.$approve.'">'.$item->employee['personnel_no'].'</span> '.$item->employee['name'];
                    $views =  $views . $html . '<br />';
                }
                return $views;
            })
            ->editColumn('aksi', function ($ski) {
                return view('ski._aksi',$ski);
            })
            ->escapeColumns([0, 1])
            ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'serverSide' => true,
            'paging' => true,
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
                'orderable' => false,
                ])
            ->addColumn([
                'data' => 'personnel_no',
                'name' => 'personnel_no',
                'title' => 'Nama',
                'class' => 'desktop',
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'month',
                'name' => 'month',
                'title' => 'Periode',
                'class' => 'desktop',
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'stage',
                'name' => 'stage',
                'title' => 'Tahapan',
                'class' => 'desktop',
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'approver',
                'name' => 'approver',
                'title' => 'Approver',
                'class' => 'desktop',
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'aksi',
                'name' => 'aksi',
                'title' => 'Aksi',
                'class' => 'desktop',
                'orderable' => false,
            ]);
        
        $stage = Stage::all();
        // tampilkan view index dengan tambahan script html DataTables
        return view('ski.index')->with(compact('html', 'overtimes','stage'));
    }

    public function create(Request $request)
    {        
        $user       = Auth::user()->personnel_no;
        $golongan   = Auth::user()->employee->esgrp;
        $golongan   = Auth::user()->employee->esgrp;
        

        $sturctDisp = Auth::user()->structDisp->where('no',1);
        $setingkat = $sturctDisp->map(function($item, $key){
            return StructDisp::where('empkostl',$item->empkostl)
                ->where('no',1)
                ->where('empnik','<>',$item->empnik)
                ->where('emppersk','like','%'.$item->emppersk[0].'%')
                ->get();
        })->first();

        $abree      = Auth::user()->employee->old_abbr;
        // mengecek apakah boleh mengajukan overtime untuk bawahan
        $allowed = Auth::user()->employee->allowedToSubmitSubordinateSki();

        $formRoute      = route('ski.store');
        $pageContainer  = 'layouts.employee._page-container';
        return view(
            'ski.create',
            compact('user', 'formRoute', 'pageContainer', 'golongan','setingkat','abree')
        );
    }

    public function store(Request $request)
    {  
        $ski = new Ski();
        $ski->personnel_no = $request->personnel_no;
        $ski->month = $request->bulan;
        $ski->year = $request->tahun;
        $ski->stage_id = 1;
        $ski->dirnik = Auth::user()->personnel_no;

        if($ski->save()){
            // $this->storeKpiShare($request,$ski);
            $this->storeKpiHasil($request,$ski);
            $this->storeKpiProses($request,$ski);
            $this->storeKpiPrilaku($request,$ski);
            $this->storeKpileadership($request,$ski);
        }
        // dd($request->input());
        return redirect()->route('ski.index');
    }

    private function storeKpiShare($request,$ski)
    {
        foreach ($request->kpi_share_aspek_penilaian as $key => $value) {
            if( $request->kpi_share_kode[$key] && $request->kpi_share_sasran_prestasi_kerja[$key] &&
                $request->kpi_share_bobot[$key] ){

                $skid = new SkiDetail();
                $skid->ski_id           = $ski->id;
                $skid->aspek_penilaian  = 'Share Capaian';
                $skid->kode             = $request->kpi_share_kode[$key];
                $skid->sasaran          = $request->kpi_share_sasran_prestasi_kerja[$key];
                $skid->ukuran           = $request->kpi_share_ukuran_prestasi_kerja[$key];
                $skid->bobot            = $request->kpi_share_bobot[$key];
                $skid->capaian          = $request->kpi_share_skor[$key];
                $skid->nilai            = $request->kpi_share_nilai[$key];
                $skid->keterangan       = 'Capaian';
                $skid->save();

            }
        }
    }

    private function storeKpiHasil($request,$ski)
    {
        foreach ($request->kpi_hasil_aspek_penilaian as $key => $value) {
            if( $request->kpi_hasil_kode[$key] && $request->kpi_hasil_sasran_prestasi_kerja[$key] &&
                $request->kpi_hasil_bobot[$key] && $request->kpi_hasil_target[$key] ){

                $skid = new SkiDetail();
                $skid->ski_id           = $ski->id;
                $skid->aspek_penilaian  = 'KPI Hasil';
                $skid->kode             = $request->kpi_hasil_kode[$key];
                $skid->sasaran          = $request->kpi_hasil_sasran_prestasi_kerja[$key];
                $skid->ukuran           = $request->kpi_hasil_ukuran_prestasi_kerja[$key];
                $skid->bobot            = $request->kpi_hasil_bobot[$key];
                $skid->target           = $request->kpi_hasil_target[$key];
                $skid->realisasi        = $request->kpi_hasil_realisasi[$key];
                $skid->capaian          = $request->kpi_hasil_capaian[$key];
                $skid->nilai            = ($request->kpi_hasil_bobot[$key] * $request->kpi_hasil_capaian[$key])/10;
                $skid->save();
            }
        }
    }

    private function storeKpiProses($request,$ski)
    {
        foreach ($request->kpi_proses_aspek_penilaian as $key => $value) {
            if( $request->kpi_proses_kode[$key] && $request->kpi_proses_sasran_prestasi_kerja[$key] &&
                $request->kpi_proses_bobot[$key] && $request->kpi_proses_target[$key] ){

                $skid = new SkiDetail();
                $skid->ski_id           = $ski->id;
                $skid->aspek_penilaian  = 'KPI Proses';
                $skid->kode             = $request->kpi_proses_kode[$key];
                $skid->sasaran          = $request->kpi_proses_sasran_prestasi_kerja[$key];
                $skid->ukuran           = $request->kpi_proses_ukuran_prestasi_kerja[$key];
                $skid->bobot            = $request->kpi_proses_bobot[$key];
                $skid->target           = $request->kpi_proses_target[$key];
                $skid->realisasi        = $request->kpi_proses_realisasi[$key];
                $skid->capaian          = $request->kpi_proses_capaian[$key];
                $skid->nilai            = ($request->kpi_proses_bobot[$key] * $request->kpi_proses_capaian[$key])/10;
                $skid->save();
            }
        }
    }

    private function storeKpiPrilaku($request,$ski)
    {
        foreach ($request->kpi_perilaku_bobot as $key => $value) {
            if( $value ){

                $skid = new SkiDetail();
                $skid->ski_id           = $ski->id;
                $skid->aspek_penilaian  = 'KPI Prilaku';
                $skid->kode             = $request->kpi_perilaku_kode[$key];
                $skid->sasaran          = $request->kpi_perilaku_sasran_prestasi_kerja[$key];
                $skid->ukuran           = $request->kpi_perilaku_ukuran_prestasi_kerja[$key];
                $skid->bobot            = $request->kpi_perilaku_bobot[$key];
                $skid->capaian          = $request->kpi_perilaku_skor[$key];
                $skid->nilai            = $request->kpi_perilaku_nilai[$key];
                $skid->keterangan       = 'Capaian';
                $skid->save();

            }
        }
    }

    private function storeKpileadership($request,$ski)
    {
        if(isset($request->kpi_leadership_aspek_penilaian)){
            foreach ($request->kpi_leadership_kode as $key => $value) {
                if( $request->kpi_leadership_kode[$key] && $request->kpi_leadership_sasran_prestasi_kerja[$key] &&
                    $request->kpi_leadership_bobot[$key] ){
    
                    $skid = new SkiDetail();
                    $skid->ski_id           = $ski->id;
                    $skid->aspek_penilaian  = 'KPI Leadership (NSP)';
                    $skid->kode             = $request->kpi_leadership_kode[$key];
                    $skid->sasaran          = $request->kpi_leadership_sasran_prestasi_kerja[$key];
                    $skid->ukuran           = $request->kpi_leadership_ukuran_prestasi_kerja[$key];
                    $skid->bobot            = $request->kpi_leadership_bobot[$key];
                    $skid->capaian          = $request->kpi_leadership_skor[$key];
                    $skid->nilai            = $request->kpi_leadership_nilai[$key];
                    $skid->keterangan       = 'Capaian';
                    $skid->save();
    
                }
            }
        }
        
    }

    public function show($id)
    {
        // $ski = Ski::find($id)->load(['stage', 'skiApproval','skiDetail']);
        $ski    = Ski::find($id);
        $personnel_no  = $ski->personnel_no;
        $skiId  = $ski->id;
        $month  = $ski->month;
        $year   = $ski->year;
        $leadership = Ski::where('month', $month)->where('year',$year)->with(['skiDetail','employee'])->get();
        $leadership = $leadership->map(function($item, $key) use($personnel_no) {
            $L = $item->skiDetail()
                ->where('aspek_penilaian','KPI Leadership (NSP)')
                ->where('kode',$personnel_no)
                ->where('ski_id',$item->id)
                ->first();
            $L = isset($L) ? $L->toArray() : [];
            return array_merge($L,$item->employee->toArray());
        });
        return view('ski.show', compact('ski', 'skiId','leadership'));
    }

    public function penilaian($id)
    {
        $ski    = Ski::find($id)->load(['stage', 'skiApproval','skiDetail']);
        $skiId  = $ski->id;
        return view('ski.penilaian', compact('ski', 'skiId'));
    }

    public function skiAtasan($bulan, $tahun)
    {
        $spt    = Auth::user()->employee->minSptBossWithDelegation()->personnel_no;
        $ski    = Ski::where('personnel_no',$spt)
                    ->where('month',$bulan)
                    ->where('year',$tahun)
                    ->with(['stage', 'skiApproval','skiDetail'])
                    ->first();
        if ($ski) {
            return view('ski.atasan', compact('ski'));
        }
        
    }

    public function edit($id)
    {
        $ski = Ski::find($id);
        $pageContainer = 'layouts.employee._page-container';
        return view('ski.edit', compact('pageContainer','ski'));
    }

    public function update(Request $request, $id)
    {
        $personnel_no = Auth::User()->personnel_no;
        $skiApproval  = SkiApproval::where('ski_id',$id)->where('regno',$personnel_no)->first();
        $skiApproval->status_id = 2;
        $skiApproval->save();

        if( $skiApproval->save() ){
            foreach ($request->ski_detail_id as $key => $value) {
                $skiDetail            = SkiDetail::find($value);
                $skiDetail->kode      = $request->kode[$key];
                $skiDetail->sasaran   = $request->sasaran[$key];
                $skiDetail->ukuran    = $request->ukuran[$key];
                $skiDetail->bobot     = $request->bobot[$key];
                $skiDetail->target    = $request->target[$key];
                $skiDetail->realisasi = $request->realisasi[$key];
                $skiDetail->capaian   = $request->capaian[$key];
                $skiDetail->kode      = $request->kode[$key];
                $skiDetail->save();
            }

            $ski = Ski::find($id);
            Session::flash("flash_notification", [
                "level" => "success",
                "message" => "Data SKI berhasil di Approve ( ID : ".$ski->id.", NIK : ".$ski->personnel_no.
                ", NAMA : ".$ski->employee->name.", BULAN : ".$ski->month.", TAHUN : ".$ski->year." )"
            ]);

        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        //
    }
}
