<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Models\AbsenceSapResponse;
use App\Models\Absence;

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
        $absence = Absence::where('stage_id','2')
        ->where('sendtosap_at','<>',null);

        if(isset($request->search['value'])){
            $absence->where(function($query) use ($request) {
                $query->orWhere('personnel_no','like', '%' . $request->search['value'] .'%' )
                ->orWhereHas('user', function ($query) use ($request) {
                    $query->where('name','like', '%' . $request->search['value'] .'%');
                })
                ->orWhereHas('absenceSapResponse', function ($query) use ($request) {
                    $query->where('desc','like', '%' . $request->search['value'] .'%');
                });
            });
        }
        
        // response untuk datatables absences
        if ($request->ajax()) {

            return Datatables::of($absence)
                ->editColumn('id', function($absence){
                    return $absence->plain_id;
                })
                ->editColumn('name', function($absence){
                    $nik = '<span class="label label-warning">'.$absence->personnel_no.'</span>';
                    return $nik.' '.$absence->user['name'];
                })
                ->editColumn('absence_type_id', function($absence){
                    return $absence->absenceType['text'];
                })
                ->addColumn('duration', function($absence){
                    $s = $absence->formatted_start_date;
                    $e = $absence->formatted_end_date;
                    $d = '<span class="label label-success">'.$absence->duration.' hari</span>';
                    return $d.' <br> '.$s.' <br> '.$e;
                }) 
                ->addColumn('status', function($absence){
                    return '<span class="label label-danger"> Error Send to SAP</span>';
                }) 
                ->addColumn('desc', function($absence){
                    $status = $absence->absenceSapResponse;
                    return $status->count() > 0 ? $status->last()->desc : ' - ';
                }) 
                ->addColumn('action', function($absence){
                    $data = [
                        'route' => 'sendtosap.absence',
                        'data' => $absence
                    ];
                    return view('sendtosap._action-button',$data);
                })
                ->escapeColumns([0,1,2])
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'paging' => true,
            'searching' => true,
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
        $data = ['switch' => 'sendtosap.attendance.index','button'=>'attendance','title'=>'absence'];
        return view('sendtosap.index')->with(compact('html', 'absences','data'));
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
}
