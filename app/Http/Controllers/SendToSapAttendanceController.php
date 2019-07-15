<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Models\Attendance;
use App\Exports\SendToSapAttendanceExport; 
use Maatwebsite\Excel\Facades\Excel;

class SendToSapAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        // ambil data cuti untuk user tersebut
        $attendance = Attendance::where('stage_id','2')
        ->where('sendtosap_at','<>',null);

        if(isset($request->search['value'])){
            $cari = explode('|', $request->search['value']);
            $month = $cari[0];
            $year = $cari[1];
            $text = $cari[2];

            if($month){
                $attendance->whereMonth('start_date',$month);
            }

            if($year){
                $attendance->whereYear('start_date',$year);
            }

            $attendance->where(function($query) use ($text) {
                $query->orWhere('personnel_no','like', '%' . $text .'%' )
                ->orWhereHas('user', function ($query) use ($text) {
                    $query->where('name','like', '%' . $text .'%');
                })
                ->orWhereHas('attendanceSapResponse', function ($query) use ($text) {
                    $query->where('desc','like', '%' . $text .'%');
                });
            });
        }
        
        // response untuk datatables absences
        if ($request->ajax()) {

            return Datatables::of($attendance)
                ->editColumn('id', function($attendance){
                    return $attendance->plain_id;
                })
                ->editColumn('name', function($attendance){
                    $nik = '<span class="label label-warning">'.$attendance->personnel_no.'</span>';
                    return $nik.' '.$attendance->user['name'];
                })
                ->editColumn('absence_type_id', function($attendance){
                    return $attendance->attendanceType['text'];
                })
                ->addColumn('duration', function($attendance){
                    $s = $attendance->formatted_start_date;
                    $e = $attendance->formatted_end_date;
                    $d = '<span class="label label-success">'.$attendance->duration.' hari</span>';
                    return $d.' <br> '.$s.' <br> '.$e;
                }) 
                ->addColumn('status', function($attendance){
                    return '<span class="label label-danger"> Error Send to SAP</span>';
                }) 
                ->addColumn('desc', function($attendance){
                    $status = $attendance->attendanceSapResponse;
                    return $status->count() > 0 ? $status->last()->desc : ' - ';
                }) 
                ->addColumn('action', function($attendance){
                    $data = [
                        'route' => 'sendtosap.attendance',
                        'data' => $attendance
                    ];
                    return view('sendtosap._action-button',$data);
                })
                ->escapeColumns([0,1,2])
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'serverSide' => true,
            'paging' => true,
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
            'switch' => 'sendtosap.absence.index',
            'download' => 'sendtosap.attendance.download',
            'button'=>'absence',
            'title'=>'attendance',
            'yearList' => Attendance::where('stage_id','2')
                            ->where('sendtosap_at','<>',null)
                            ->foundYear()->get(),

            'monthList' => Attendance::where('stage_id','2')
                            ->where('sendtosap_at','<>',null)
                            ->foundMonth()->get()
        ];
        return view('sendtosap.index')->with(compact('html', 'attendance','data'));
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
        $absence = Attendance::find($id);
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
        $absence = Attendance::find($id);
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

        return (new SendToSapAttendanceExport)
            ->forMonth($bulan)
            ->forYear($tahun)
            ->download('SendToSapAttendance'.$bulan.$tahun.'.xlsx');
    }
}
