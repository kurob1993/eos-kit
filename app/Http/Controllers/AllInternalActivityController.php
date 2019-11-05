<?php

namespace App\Http\Controllers;

use App\Exports\InternalActivityExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Datatables;
use Illuminate\Http\Request;
use App\Models\InternalActivity;
use App\Models\Stage;
use Carbon\Carbon;

class AllInternalActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        // ambil data cuti untuk user tersebut
        $activity = InternalActivity::where('id','<>',null);
        if (isset($request->search['value'])) {
            $cari = explode('|', $request->search['value']);
            $month = $cari[0];
            $year = $cari[1];
            $stage = $cari[2];

            if ($month) {
                $activity->whereMonth('start_date', $month);
            }

            if ($year) {
                $activity->whereYear('start_date', $year);
            }

            if ($stage) {
                $activity->where('stage_id', $stage);
            }
            
        }

        // response untuk datatables absences
        if ($request->ajax()) {
            return Datatables::of($activity)
                ->editColumn('id', function ($activity) {
                    return $activity->plain_id;
                })
                ->editColumn('personnel_no', function ($activity) {
                    $nik = '<span class="label label-primary">' . $activity->personnel_no . '</span>';
                    return $nik . ' ' . $activity->user['name'];
                })
                ->editColumn('start_date', function ($activity) {
                    return $activity->start_date->format('d.m.Y');
                })
                ->editColumn('end_date', function ($activity) {
                    return $activity->end_date->format('d.m.Y');
                })
                ->editColumn('internal_activity_position_id', function ($activity) {
                    return $activity->position->text;
                })
                ->editColumn('aksi', function ($activity) {
                    return view('all_internal_activity._action',compact('activity'));
                })
                ->escapeColumns([0, 1])
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'serverSide' => true,
            'paging' => true,
            'ordering' => true,
            'sDom' => 'tpi',
            'responsive' => true,
        ]);

        $html = $htmlBuilder
            ->addColumn([
                'data' => 'id',
                'name' => 'id',
                'title' => 'ID',
                'searchable' => false,
                'orderable' => true,
            ])
            ->addColumn([
                'data' => 'personnel_no',
                'name' => 'personnel_no',
                'title' => 'Nama',
                'searchable' => false,
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'jenis_kegiatan',
                'name' => 'jenis_kegiatan',
                'title' => 'Jenis Kegiatan',
                'searchable' => false,
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'internal_activity_position_id',
                'name' => 'internal_activity_position_id',
                'title' => 'Posisi',
                'class' => 'none',
                'searchable' => false,
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'start_date',
                'name' => 'start_date',
                'title' => 'Mulai',
                'searchable' => false,
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'end_date',
                'name' => 'end_date',
                'title' => 'Berakhir',
                'searchable' => false,
                'orderable' => false,
            ])
            // ->addColumn([
            //     'data' => 'keterangan',
            //     'name' => 'keterangan',
            //     'title' => 'Keterangan',
            //     'class' => 'none',
            //     'searchable' => false,
            //     'orderable' => false,
            // ])
            ->addColumn([
                'data' => 'aksi',
                'name' => 'aksi',
                'title' => 'Aksi',
                'searchable' => false,
                'orderable' => false,
            ]);

        $data = [
            'monthList' => InternalActivity::monthList()->get(),
            'yearList' => InternalActivity::yearList()->get(),
        ];
        $stage = Stage::all();
        // tampilkan view index dengan tambahan script html DataTables
        return view('all_internal_activity.index')->with(compact('html', 'activity', 'data','stage'));
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
        $activity = InternalActivity::find($id);
        $activity->stage_id = 3;
        $activity->save();
        return redirect()->back(); 
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

    public function export(Request $request) 
    {
        ob_end_clean();
        ob_start();

        $bulan = (int)$request->month;
        $tahun = (int)$request->year;
        $stage = (int)$request->stage;

        return (new InternalActivityExport)
            ->forMonth($bulan)
            ->forYear($tahun)
            ->forStage($stage)
            ->download('InternalActivity_'.$bulan.$tahun.'.xlsx');
    }
}
