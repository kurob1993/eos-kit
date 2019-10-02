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
use App\Models\CompanyPosisition;
use App\Models\Company;
use App\Models\SAP\Zhrom0007;
use App\Models\SAP\StructDisp;
use App\Http\Controllers\API\StructDispController;
use App\Http\Controllers\API\Zhrom0007Controller;


class DislikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
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

                // data preference and dislike
                $getPreferdis = Preferdis::where('preferdis_periode_id', $dataperiode->id)
                    ->where('sobid', Auth::user()->personnel_no)
                    ->get()
                    ->toArray();

                $dataPreferdis = array_column($getPreferdis, 'seark');
                // dd($getPreferdis);

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
                    ->where('relat', '043')
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
                    return view('dislikes.create-ab')
                    ->with(compact('pref', 'level', 'dataperiode','companies','preferNotSameLevel','preferSameLevel','dataPreferdis'));
                }
                else
                {
                    // tampilkan view create
                    return view('dislikes.create')->with(compact('pref', 'level', 'dataperiode','preferNotSameLevel','preferSameLevel','dataPreferdis'));
                }
                // tampilkan view create
                // return view('dislikes.create')->with(compact('pref', 'level', 'dataperiode'));
            }
            else
            {
                // tampilkan view create
                return view('dislikes._close');
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
            "message" => "Berhasil menyimpan data dislike.",
        ]);
        
        // test data
        // dd($request->input('abbrPosisitionNew'));
        
        // inisiasi variabel
        $dateNow    = Carbon::now()->toDateTimeString();
        $jabatan    = $request->input('abbrPosisitionNew');

        // insert data  
        foreach($jabatan as $item)
        {
            // check item null or not
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

                // save to table preferdis
                $referdis = Preferdis::create(
                    [
                        'preferdis_periode_id' => $request->input('periode'),
                        'otype'     => 'S',
                        'seark'     => $item,
                        'stext'     => $stext->NameofPosition,
                        'begda'     => $dateNow,
                        'enda'      => '9999-12-31',
                        'rsign'     => 'B',
                        'relat'     => '043',
                        'sclas'     => 'P',
                        'sobid'     => Auth::user()->personnel_no,
                        'status'    => '0'
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
}
