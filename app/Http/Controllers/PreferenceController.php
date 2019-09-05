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
use App\Models\Preferdis;
use App\Models\PreferdisPeriode;
use App\Models\Company;
use App\Models\CompanyPosisition;
use App\Models\SAP\Zhrom0007;
use App\Models\SAP\StructDisp;
use App\Http\Controllers\API\StructDispController;
use App\Http\Controllers\API\Zhrom0007Controller;
use App\Exports\PreferdisExport;
use Maatwebsite\Excel\Facades\Excel;


class PreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        $dateNow = Carbon::now()->toDateString();

        // cek data periode
        $datacek = PreferdisPeriode::where('finish_date', '>=', $dateNow)
            ->where('start_date', '<=', $dateNow)
            ->get()
            ->count();

         // getdata periode
         $dataperiode = PreferdisPeriode::all();

        // tampilkan view index dengan tambahan script html DataTables
        $pref = "Data Preference dan Dislike";
        $preferdis = Preferdis::ofLoggedUser()
            ->get();

        // response untuk datatables preferdis
        if ($request->ajax()) {

            return Datatables::of($preferdis)
                ->editColumn('name', function ($preferece) {
                    $nik = '<span class="label label-warning">'.$preferece->sobid.'</span>';
                    return $nik." ".$preferece->user->name;
                })
                ->editColumn('posisition', function ($preferece) {
                    return  $preferece->stext;
                })
                ->editColumn('profile', function ($preferece) {
                    // id preferdis
                    return  '<span class="label label-primary">'.$preferece->profileName.'</span>';
                })
                ->editColumn('periode', function ($preferece) {
                    return  '<span class="label label-primary">'.$preferece->preferdisPeriode->start_date.'</span> <span class="label label-primary">'.$preferece->preferdisPeriode->finish_date."</span>";
                })
                ->editColumn('date', function ($preferece) {
                    return  $preferece->begda;
                })
                ->editColumn('action', function ($preferece) {
                    // id preferdis
                    $views = '';
                        $views =  $views . view('preferences._action', [
                            'id' => $preferece->id
                        ]) . '<br />';
                        
                    return $views;
                })
                
                // ->setRowAttr([
                //     // href untuk dipasang di setiap tr
                //     'data-href' => function ($preferece) {
                //         return route('preference.show', ['leaf' => $preferece->id]);
                //     } 
                // ])
                ->escapeColumns([0,1])
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'serverSide' => true,
            'paging' => true,
            'ordering'=> true,
            'sDom' => 'tpi',
            'responsive' => [ 'details' => true ],
        ]);

        $html = $htmlBuilder
        ->addColumn([
            'data' => 'name',
            'name' => 'name',
            'title' => 'Name',
            'searchable' => false,
            'orderable' => false, 
        ])
        ->addColumn([
            'data' => 'posisition',
            'name' => 'posisition',
            'title' => 'Position',
            'searchable' => false,
            'orderable' => false, 
        ])
        ->addColumn([
            'data' => 'profile',
            'name' => 'profile',
            'title' => 'Profile',
            'searchable' => false,
            'orderable' => false,
        ])
        ->addColumn([
            'data' => 'periode',
            'name' => 'periode',
            'title' => 'Periode',
            'searchable' => true,
            'orderable' => false,
        ])
        ->addColumn([
            'data' => 'date',
            'name' => 'date',
            'title' => 'Created',
            'searchable' => false,
            'orderable' => false,
        ])
        ->addColumn([
            'data' => 'action',
            'name' => 'action',
            'title' => 'Action',
            'searchable' => false,
            'orderable' => false,
        ]);

        // tampilkan view index dengan tambahan script html DataTables
        return view('preferences.index')->with(compact('html', 'preferdis','pref','datacek','dataperiode'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Builder $htmlBuilder)
    {
        try {
            $dateNow    = Carbon::now()->toDateString();

            // cek data periode
            $cek = PreferdisPeriode::where('finish_date', '>=', $dateNow)
                ->where('start_date', '<=', $dateNow)
                ->get()
                ->count();
            
            if($cek > 0)
            {   
                // get data periode
                $dataperiode = PreferdisPeriode::where('finish_date', '>=', $dateNow)
                    ->where('start_date', '<=', $dateNow)
                    ->first();

                // get data structdisp
                $strucDisp = new StructDispController();
                $dataDisp = $strucDisp->show(Auth::user()->personnel_no);

                // get level jabatan
                $level = substr($dataDisp['esgrp'],0,1);
            
                // cek periode
                $prefer = Preferdis::withCount(['zhrom0007' => function ($query) use($level) {
                    $query->where('LvlOrg', $level);
                }])
                    ->where('sobid', Auth::user()->personnel_no)
                    ->where('preferdis_periode_id', $dataperiode->id)
                    ->where('relat', '042')
                    ->get();

                $preferSameLevel = $prefer->where('zhrom0007_count', '<>', 0)->count();

                $allPrefer = $prefer->count();

                $preferNotSameLevel  = $allPrefer -  $preferSameLevel;

                // get data perusahaan
                $companies = Company::all();

                // title  
                $pref ="Data Preference dan Dislike";
            
                // cek level jabatan
                if($level == 'A' || $level == 'B')
                {
                    // tampilkan view create
                    return view('preferences.create-ab')->with(compact('pref', 'level', 'dataperiode','companies','preferNotSameLevel','preferSameLevel'));
                }
                else
                {
                    // tampilkan view create
                    return view('preferences.create')->with(compact('pref', 'level', 'dataperiode','preferNotSameLevel','preferSameLevel'));
                }
            }
            else {
                 // tampilkan view close
                 return view('preferences._close');
            }

        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa tidak ada data karyawan yang bisa ditemukan
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Silahkan hubungi Divisi HCI&A."
            ]);
            
            // batalkan view create dan kembali ke parent
            return redirect()->route('preferences.index');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // tampilkan pesan bahwa telah berhasil diinput
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan preference.",
        ]);
        
        // test data
        // dd($request->input('abbrPosisitionNew'));
        
        // inisiasi variabel
        $dateNow    = Carbon::now()->toDateTimeString();
        $jabatan    = $request->input('abbrPosisitionNew');

        // insert data  
        foreach($jabatan as $item)
        {
            // check data ada atau tidak
            if($item != "")
            {
                // get name off posisition
                $stext  = Zhrom0007::where('AbbrPosition',$item)->first();
                if(isset($stext->NameofPosition))
                {
                    $stextname = $stext->NameofPosition;
                }
                else
                {
                    $stext1 = CompanyPosisition::where('AbbrPosition',$item)->first();
                    $stextname = $stext1->NameofPosition;
                }

                // input to tabel preferdis 
                $referdis = Preferdis::create(
                    [
                        'preferdis_periode_id'  => $request->input('periode'),
                        'otype'                 => 'S',
                        'seark'                 => $item,
                        'stext'                 => $stextname,
                        'begda'                 => $dateNow,
                        'enda'                  => '9999-12-31',
                        'rsign'                 => 'B',
                        'relat'                 => '042',
                        'sclas'                 => 'P',
                        'sobid'                 => Auth::user()->personnel_no,
                        'status'                => '0'
                                                
                    ]
                );
            }
        }
             
        // kembali ke halaman index preferences
        return redirect()->route('preference.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // cek jenis data dislike or preference
            $getdata = Preferdis::find($id);

            if($getdata->relat == "042") {

                // tampilkan pesan bahwa telah berhasil diinput
                Session::flash("flash_notification", [
                    "level" => "success",
                    "message" => "Berhasil menghapus data preference.",
                ]);
            } else {
                // tampilkan pesan bahwa telah berhasil diinput
                Session::flash("flash_notification", [
                    "level" => "success",
                    "message" => "Berhasil menghapus data dislikes.",
                ]);
            }

            // hapus data
            $preferdis = Preferdis::find($id);
            $preferdis->delete();
            return redirect()->route('preference.index');

        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa ada kegagalan system
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Gagal manghapus data, silahkan hubungi Divisi HCI&A."
            ]);
            
            // batalkan view create dan kembali ke parent
            return redirect()->route('preferences.index');
        }
    }

    public function preferncesDashboard(Request $request, Builder $htmlBuilder) 
    {
        // getdata periode
        $dataperiode = PreferdisPeriode::all();

       // tampilkan view index dengan tambahan script html DataTables
       $pref = "Data Preference dan Dislike";
       $preferdis = Preferdis::all();

       // response untuk datatables preferdis
       if ($request->ajax()) {

           return Datatables::of($preferdis)
                ->editColumn('name', function ($preferece) {
                    $nik = '<span class="label label-warning">'.$preferece->sobid.'</span>';
                    return $nik." ".$preferece->user->name;
                })
                ->editColumn('posisition', function ($preferece) {
                    return  $preferece->stext;
                })
                ->editColumn('periode', function ($preferece) {
                    return  '<span class="label label-primary">'.$preferece->preferdisPeriode->start_date.'</span> <span class="label label-primary">'.$preferece->preferdisPeriode->finish_date."</span>";
                })
                ->editColumn('profile', function ($preferece) {
                   // id preferdis
                   return  '<span class="label label-primary">'.$preferece->profileName.'</span>';
                })
                
               // ->setRowAttr([
               //     // href untuk dipasang di setiap tr
               //     'data-href' => function ($preferece) {
               //         return route('preference.show', ['leaf' => $preferece->id]);
               //     } 
               // ])
               ->escapeColumns([0,1])
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
            [ "width" => "30%", "targets" => 0 ],
            [ "width" => "40%", "targets" => 1 ] 
        ]
       ]);

       $html = $htmlBuilder
            ->addColumn([
                'data' => 'name',
                'name' => 'name',
                'title' => 'Name',
                'searchable' => false,
                'orderable' => false, 
                ])
           ->addColumn([
               'data' => 'posisition',
               'name' => 'posisition',
               'title' => 'Position',
               'searchable' => false,
               'orderable' => false, 
               ])
            ->addColumn([
                'data' => 'periode',
                'name' => 'periode',
                'title' => 'Periode',
                'searchable' => true,
                'orderable' => false,
                ])
           ->addColumn([
               'data' => 'profile',
               'name' => 'profile',
               'title' => 'Profile',
               'searchable' => false,
               'orderable' => false,
               ]);

       // tampilkan view index dengan tambahan script html DataTables
       return view('preferences.form-export')->with(compact('html', 'preferdis','pref','dataperiode'));

    }

    public function download(Request $request) 
    {
        $periode = (int)$request->periode;

        return (new PreferdisExport)
            ->forPeriode($periode)
            ->download('preferdis'.$periode.'.xlsx');
    }
}
