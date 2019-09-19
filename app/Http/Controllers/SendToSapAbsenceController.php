<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Models\AbsenceSapResponse;
use App\Models\Absence;
use App\Exports\SendToSapAbsenceExport;
use Maatwebsite\Excel\Facades\Excel;

class SendToSapAbsenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        // ambil data cuti untuk user tersebut
        $absence = Absence::where('stage_id', '2')
        ->where('sendtosap_at', '<>', null);

        if (isset($request->search['value'])) {
            $cari = explode('|', $request->search['value']);
            $month = $cari[0];
            $year = $cari[1];
            $text = $cari[2];

            if ($month) {
                $absence->whereMonth('start_date', $month);
            }

            if ($year) {
                $absence->whereYear('start_date', $year);
            }

            $absence->where(function ($query) use ($text) {
                $query->orWhere('personnel_no', 'like', '%' . $text .'%')
                ->orWhereHas('user', function ($query) use ($text) {
                    $query->where('name', 'like', '%' . $text .'%');
                })
                ->orWhereHas('absenceSapResponse', function ($query) use ($text) {
                    $query->where('desc', 'like', '%' . $text .'%');
                });
            });
        }
        
        // response untuk datatables absences
        if ($request->ajax()) {
            return Datatables::of($absence)
                ->editColumn('id', function ($absence) {
                    return $absence->plain_id;
                })
                ->editColumn('name', function ($absence) {
                    $nik = '<span class="label label-warning">'.$absence->personnel_no.'</span>';
                    return $nik.' '.$absence->user['name'];
                })
                ->editColumn('absence_type_id', function ($absence) {
                    return $absence->absenceType['text'];
                })
                ->addColumn('duration', function ($absence) {
                    $s = $absence->formatted_start_date;
                    $e = $absence->formatted_end_date;
                    $d = '<span class="label label-success">'.$absence->duration.' hari</span>';
                    return $d.' <br> '.$s.' <br> '.$e;
                })
                ->addColumn('status', function ($absence) {
                    return '<span class="label label-danger"> Error Send to SAP</span>';
                })
                ->addColumn('desc', function ($absence) {
                    $status = $absence->absenceSapResponse;
                    return $status->count() > 0 ? $status->last()->desc : ' - ';
                })
                ->addColumn('action', function ($absence) {
                    $data = [
                        'route' => 'sendtosap.absence',
                        'data' => $absence
                    ];
                    return view('sendtosap._action-button', $data);
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
                [ "width" => "12%", "targets" => 0 ],
                [ "width" => "20%", "targets" => 1 ]
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
                'data' => 'name',
                'name' => 'absences.name',
                'title' => 'NAME',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'absence_type_id',
                'name' => 'absence_type_id',
                'title' => 'TYPE',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'duration',
                'name' => 'duration',
                'title' => 'DURATION',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'status',
                'name' => 'status',
                'title' => 'STATUS',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'desc',
                'name' => 'desc',
                'title' => 'DESC',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'action',
                'name' => 'action',
                'title' => 'ACTION',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ]);

        // tampilkan view index dengan tambahan script html DataTables
        $data = [
            'switch' => 'sendtosap.attendance.index',
            'download' => 'sendtosap.absence.download',
            'button'=>'attendance',
            'title'=>'absence',
            'yearList' => Absence::where('stage_id', '2')
                            ->where('sendtosap_at', '<>', null)
                            ->foundYear()->get(),

            'monthList' => Absence::where('stage_id', '2')
                            ->where('sendtosap_at', '<>', null)
                            ->foundMonth()->get()
        ];
        return view('sendtosap.index')->with(compact('html', 'absence', 'data'));
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
        $absence = Absence::find($id);
        $absence->sendtosap_at = null;
        $absence->save();
        
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Data berhasil dimasukan ke dalam antrian untuk di preoses ulang.",
        ]);

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
        $absence = Absence::find($id);
        $absence->stage_id = 4;
        $absence->save();

        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Data berhasil di Failed",
        ]);

        return redirect()->back();
    }

    public function download(Request $request)
    {
        ob_end_clean();
        ob_start();

        $bulan = (int)$request->month;
        $tahun = (int)$request->year;

        return (new SendToSapAbsenceExport)
            ->forMonth($bulan)
            ->forYear($tahun)
            ->download('SendToSapAbsence'.$bulan.$tahun.'.xlsx');
    }
}
