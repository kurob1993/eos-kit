<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Http\Requests\StoreTravelRequest;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\AbsenceQuota;
use App\Models\Travel;
use App\Models\TravelApproval;
use App\Models\TravelType;
use App\Models\Transition;
use App\Models\City;

class TravelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        $personnel_no = Auth::user()->employee->personnel_no;
        $Travel = Travel::where('personnel_no', $personnel_no)->get();
        if ($request->ajax()) {
            return Datatables::of($Travel)
            ->editColumn('summary', function ($Travel) {
                // kolom summary menggunakan view _summary
                return view('travels._summary', [ 
                    'summary' => $Travel,
                    'when' => $Travel->created_at->format('d/m') 
                ]);
            })
            ->editColumn('approver', function ($Travel) {
                // personnel_no dan name atasan
                    $views = '';
                    foreach ($Travel->travelApproval as $item) {
                        $views =  $views . view('layouts._personnel-no-with-name', [
                            'personnel_no' => $item->employee['personnel_no'],
                            'employee_name' => $item->employee['name'],
                        ]) . '<br />';
                    }
                return $views;
            })
            ->setRowAttr([
                // href untuk dipasang di setiap tr
                'data-href' => function ($Travel) {
                    return route('travels.show', ['travel' => $Travel->id]);
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

        return view('travels.index')->with(compact('html', 'Travel'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            // mendapatkan data employee dari user
            // dan mengecek apakah dapat melakukan pelimpahan
            $canDelegate = Auth::user()->employee()->firstOrFail()->canDelegate();
        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa tidak ada data karyawan yang bisa ditemukan
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Tidak ditemukan data karyawan. Silahkan hubungi Divisi HCI&A."
            ]);
            // batalkan view create dan kembali ke parent
            return redirect()->route('travels.index');
        } 

        $gol = Auth::user()->employee->esgrp;
        $cc = Auth::user()->employee->cost_ctr;
        $travelType = TravelType::all();
        $city = City::orderBy('text','ASC')->get();

        return view('travels.create', 
            [
                'can_delegate' => $canDelegate,
                'gol' => $gol,
                'cc' => $cc,
                'travelType' => $travelType,
                'city' => $city
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $travel = new Travel();
        $travel->personnel_no = $request->personnel_no;
        $travel->start_date = $request->start_date;
        $travel->end_date = $request->end_date;
        $travel->tujuan = $request->tujuan;
        $travel->keperluan = $request->keperluan;
        $travel->jenis_spd = $request->travel_type;
        $travel->kendaraan = $request->kendaraan;
        $travel->kota = $request->pilih_kota;
        $travel->nopol = $request->nopol;
        $travel->stage_id = 1;  
        if ($request->lampiran) {
            $data = $request->lampiran->store('travel', 'public');
            $travel->lampiran = $data;
        }

        if($travel->save()){
            $this->approval($travel->id,$request->minManagerBoss);
            if ($request->has('managerGa')) {
                $this->approval_ga($travel->id, $request->managerGa);
            }
            if($request->delegation){
                $abbr = Auth::user()->structDisp()->first()->emp_hrp1000_s_short;
                $nik = $request->delegation;
                $this->storeDelegation($nik,$abbr,$travel->start_date,$travel->end_date);
            }
        }
        
        return redirect()->route('travels.index');
    }

    public function approval_ga($id,$ga)
    {
        $ta = new TravelApproval();
        $ta->travel_id = $id;
        $ta->regno = $ga;
        $ta->status_id = 1;
        $ta->save();
    }


    public function approval($id,$boss)
    {
        $ta = new TravelApproval();
        $ta->travel_id = $id;
        $ta->regno = $boss;
        $ta->status_id = 1;
        $ta->save();
    }

    public function storeDelegation($personnel_no,$abbr,$start,$end)
    {
        $ta = new Transition();
        $ta->abbr_jobs = $abbr;
        $ta->personnel_no = $personnel_no;
        $ta->start_date = $start;
        $ta->end_date = $end;
        $ta->save();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $travel = Travel::find($id)
            ->load(
                [
                    'stage',
                    'travelApproval'
                ]
            );

        $travelId = $travel->id;
        
        return view('travels.show', compact('travel', 'travelId'));
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

    public function download($id){
        $data = Travel::findOrFail($id);
        return response()->download(storage_path('travel',$data->lampiran));
    }
}
