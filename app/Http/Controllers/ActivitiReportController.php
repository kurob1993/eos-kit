<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;

use App\Models\ActivitiReport;

class ActivitiReportController extends Controller
{
   public function index(Request $request, Builder $htmlBuilder)
   {
        $nik = Auth::User()->personnel_no;
        $activity = ActivitiReport::where('Pernr',$nik);

        // response untuk datatables absences
        if ($request->ajax()) {
            return Datatables::of($activity)
            ->editColumn('tanggal', function($activity){
                $tanggal = date('d . m . Y',strtotime($activity->tanggal));
                return $tanggal;
            })
            ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'serverSide' => true,
            'paging' => true,
            'ordering'=> true,
            'sDom' => 'tpi',
            'autoWidth' => false,
            'responsive' => true,
            "columnDefs" => [ 
                [ "width" => "35%", "targets" => 0 ], 
                [ "width" => "30%", "targets" => 3 ], 
                [ "width" => "30%", "targets" => 4 ], 
            ]
        ]);
        
        $html = $htmlBuilder
            ->addColumn([
                'data' => 'tanggal',
                'name' => 'tanggal',
                'title' => 'TANGGAL',
            ])
            ->addColumn([
                'data' => 'hari',
                'name' => 'hari',
                'title' => 'HARI',
            ])
            ->addColumn([
                'data' => 'tprog',
                'name' => 'rule',
                'title' => 'RULE',
            ])
            ->addColumn([
                'data' => 'sobeg-soend',
                'name' => 'rencana',
                'title' => 'RENCANA JAM KERJA',
            ])
            ->addColumn([
                'data' => 'itime-otime',
                'name' => 'aktual',
                'title' => 'AKTUAL JAM KERJA',
            ])
            ->addColumn([
                'data' => 'beguz-enduz',
                'name' => 'renclembur',
                'title' => 'RENCANA JAM LEMBUR',
            ])
            ->addColumn([
                'data' => 'late',
                'name' => 'lambatcepat',
                'title' => 'LAMBAT / CEPAT',
            ])
            ->addColumn([
                'data' => 'wtact',
                'name' => 'jamkerjakatual',
                'title' => 'J_KER AKTUAL',
            ])
            ->addColumn([
                'data' => 'otact',
                'name' => 'lemburaktual',
                'title' => 'LEMBUR AKTUAL',
            ])
            ->addColumn([
                'data' => 'otaut',
                'name' => 'lemburauto',
                'title' => 'LEMBUR AUTO',
            ])
            ->addColumn([
                'data' => 'othit',
                'name' => 'lemburhitung',
                'title' => 'LEMBUR HITUNG',
            ])
            ->addColumn([
                'data' => 'ijin',
                'name' => 'ijin',
                'title' => 'IJIN',
            ])
            ->addColumn([
                'data' => 'cuti',
                'name' => 'cuti',
                'title' => 'CUTI',
            ])
            ->addColumn([
                'data' => 'lain',
                'name' => 'lain',
                'title' => 'LAIN',
            ])
            ->addColumn([
                'data' => 'keterangan',
                'name' => 'keterangan',
                'title' => 'KETERANGAN',
            ]);

        return view('activity.index')->with(compact('html', 'activity'));
   }
}
