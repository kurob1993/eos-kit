<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Models\Ski;
use App\Models\Stage;
use App\Http\Controllers\API\StructDispController;
use App\Models\SAP\OrgText;
use App\Exports\SkiExport;
use Maatwebsite\Excel\Facades\Excel;

class AllSkiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        // data divisi
        $divisi = OrgText::where('EndDate','9999-12-31')
            ->where('Objectname','like','Divisi%')
            ->distinct()
            ->get(['Objectname','ObjectID','EndDate']);

        // dd($divisi);

        // ambil data cuti untuk user tersebut
        $ski = Ski::with('stage');

        if(isset($request->search['value'])){
            $cari = explode('|', $request->search['value']);
            $month = $cari[0];
            $year = $cari[1];
            $text = $cari[2];
            $stage = $cari[3];
            $nik = explode(',', $text);
            if($month){
                $ski->where('month',$month);
            }

            if($year){
                $ski->where('year',$year);
            }

            if($stage){
                $ski->where('stage_id',$stage);
            }
            if($text){
                $ski->whereIn('personnel_no',$nik);
            }
           
        }
        
        // response untuk datatables skis
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
                    return number_format((float)$nilai, 2,'.', '');
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
                    return number_format((float)$nilai, 2,'.', '');
                    
                })
                ->editColumn('divisi', function (Ski $Ski) {   
                    $strucdisp = new StructDispController();
                    $data = $strucdisp->show($Ski->personnel_no);      
                    return $data['divisi'];                    
                })
                ->editColumn('detail', function (Ski $Ski) {
                    return view('secretary.ski._action',['ski'=>$Ski] );
                })
                ->escapeColumns([0,1,2])
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'serverSide' => true,
            'paging' => true,
            'ordering'=> true,
            'sDom' => 'tpi',
            'responsive' => [ 'details' => true ],
            "columnDefs" => [ 
                [ "width" => "10%", "targets" => 0 ]
            ]
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
                'data' => 'divisi',
                'name' => 'divisi',
                'title' => 'Divisi',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ])->addColumn([
                'data' => 'detail',
                'name' => 'detail',
                'title' => 'Detail',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ]);

        // tampilkan view index dengan tambahan script html DataTables
        $data = [
            'switch' => 'sendtosap.attendance.index',
            'download' => 'sendtosap.ski.download',
            'button'=>'attendance',
            'title'=>'Sasaran Kinerja Individu',
            'yearList' => ski::foundYear()->get(),
            'monthList' => ski::foundMonth(),
            'stage' => Stage::all(),
            'divisi' => $divisi,
        ];
        return view('all_ski.index')->with(compact('html', 'ski','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function download(Request $request) 
    {
        ob_end_clean();
        ob_start(); 

        $bulan = $request->month;
        $tahun = $request->year;
        $stage = $request->stage;
        $text = $request->text;
        $type = $request->type;
        $divisi = $request->divisi;
        
        // dd($divisi.''.$stage);

        return (new SkiExport)
            ->forMonth($bulan)
            ->forYear($tahun)
            ->forStage($stage)
            ->forText($text)
            ->forType($type)
            ->forDivisi($divisi)
            ->download('Ski.xlsx');
    }
}
