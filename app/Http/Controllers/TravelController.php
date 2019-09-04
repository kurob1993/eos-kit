<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\AbsenceQuota;
use App\Models\Travel;
use App\Models\TravelApproval;
use App\Models\Transition;

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
                ->editColumn('id', function ($Travel) {
                    return $Travel->plain_id;
                })
                ->editColumn('personnel_no', function ($Travel) {
                    return $Travel->employee->name;
                })
                ->editColumn('personnel_no', function ($Travel) {
                    $name = $Travel->employee->name;
                    $code = '<label class="label label-info">'.
                        $Travel->employee->personnel_no
                    .'</label>';
                    return $code.' '.$name;
                })
                ->editColumn('start_date', function ($Travel) {
                    return $Travel->formatted_start_date;
                })
                ->editColumn('end_date', function ($Travel) {
                    return $Travel->formatted_end_date;
                })
                ->editColumn('atasan', function ($Travel) {
                    $ta = $Travel->TravelApproval->first();
                    $name = '';
                    $code = '';
                    if ($ta) {
                        $name = $ta->employee->name;
                        $code = '<label class="label label-info">'.
                            $ta->employee->personnel_no
                        .'</label>';
                    }
                    return $code.' '.$name;
                })
                ->editColumn('stage_id', function ($Travel) {
                    return '<span class="label label-' . $Travel->stage->class_description . '">' 
                    . $Travel->stage->description . '</span>';
                })
                ->editColumn('kendaraan', function ($Travel) {
                    if($Travel->kendaraan == 'Dinas'){
                        $re = '<span class="label label-primary">'. $Travel->nopol . '</span> '. $Travel->kendaraan;
                    }else{
                        $re = '<span class="label label-primary">'. $Travel->kendaraan . '</span>';
                    }
                    return $re;
                })
                ->escapeColumns([0,1,6])
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'paging' => true,
            'searching' => true,
            'responsive' => [ 'details' => true ]
        ]);

        $html = $htmlBuilder
        ->addColumn([
            'data' => 'id',
            'name' => 'id',
            'title' => 'ID',
            'searchable' => true,
            'orderable' => false, 
            ])
        ->addColumn([
            'data' => 'personnel_no',
            'name' => 'personnel_no',
            'title' => 'Karyawan',
            'searchable' => true,
            'orderable' => false,
        ])
        ->addColumn([
            'data' => 'start_date',
            'name' => 'start_date',
            'title' => 'Tgl Mulai',
            'class' => 'desktop',
            'searchable' => false,
            'orderable' => false,
        ])
        ->addColumn([
            'data' => 'end_date',
            'name' => 'end_date',
            'title' => 'Tgl Selesai',
            'class' => 'desktop',
            'searchable' => false,
            'orderable' => false,
        ])
        ->addColumn([
            'data' => 'tujuan',
            'name' => 'tujuan',
            'title' => 'Tujuan',
            'class' => 'desktop',
            'searchable' => false,
            'orderable' => false,
        ])
        ->addColumn([
            'data' => 'keperluan',
            'name' => 'keperluan',
            'title' => 'Keperluan',
            'class' => 'desktop',
            'searchable' => false,
            'orderable' => false,
        ])
        ->addColumn([
            'data' => 'atasan',
            'name' => 'atasan',
            'title' => 'Atasan',
            'class' => 'none',
            'searchable' => false,
            'orderable' => false,
        ])
        ->addColumn([
            'data' => 'kendaraan',
            'name' => 'kendaraan',
            'title' => 'Kendaraan',
            'class' => 'none',
            'searchable' => false,
            'orderable' => false,
        ])
        ->addColumn([
            'data' => 'stage_id',
            'name' => 'stage',
            'title' => 'Tahapan',
            'class' => 'none',
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

        return view('travels.create', [
            'can_delegate' => $canDelegate
        ]);
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
        $travel->kendaraan = $request->kendaraan;
        $travel->nopol = $request->nopol;
        $travel->stage_id = 1;

        if($travel->save()){
            $this->approval($travel->id,$request->minManagerBoss);
            if($request->delegation){
                $abbr = Auth::user()->structDisp()->first()->emp_hrp1000_s_short;
                $nik = $request->delegation;
                $this->storeDelegation($nik,$abbr,$travel->start_date,$travel->end_date);
            }
        }
        
        return redirect()->route('travels.index');
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
