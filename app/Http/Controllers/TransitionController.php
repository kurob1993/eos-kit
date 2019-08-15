<?php

namespace App\Http\Controllers;

use Session;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Http\Requests\StoreTransitionRequest;
use Illuminate\Http\Request;
use App\Models\SAP\Zhrom0007;
use App\Models\SAP\Zhrom0013;
use App\Models\SAP\StructJab;
use App\Models\SAP\StructDisp;
use App\Models\Transition;

class TransitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        $transition = Transition::where('actived_at','<>',null)
        ->where('end_date','>=', date('Y-m-d') )
        ->with(['zhrom0007','user'])
        ->orderBy('start_date','desc')
        ->get();

        if ($request->ajax()) {
            return Datatables::of($transition)
            ->editColumn('abbr_jobs', function ($transition) {
                $jabaran = $transition->structJab->first();
                $code = '<label class="label label-info">'.
                    $jabaran['no']
                .'</label>';
                $name = $jabaran['jabatan'];
                return $code.' '.$name;
            })
            ->editColumn('personnel_no', function ($transition) {
                $user = $transition->user->first();
                $code = '<label class="label label-info">'.
                    $user['personnel_no']
                .'</label>';
                return $code.' '.$user['name'];
            })
            ->addColumn('action', function($transition){
                return view('components._action-delete',[
                    'model'=>$transition,
                    'delete_url' => route('personnel_service.delete', ['id' => $transition->id, 'approval' => 'delegation' ] )
                ]);
            })
            ->escapeColumns([0,1])
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
            'data' => 'abbr_jobs',
            'name' => 'abbr_jobs',
            'title' => 'Jabatan',
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
            'data' => 'action',
            'name' => 'action',
            'title' => 'Aksi',
            'class' => 'desktop',
            'searchable' => false,
            'orderable' => false,
        ]);
        
        return view('transition.index')->with(compact('html', 'transition'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('transition.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransitionRequest $request)
    {
        // tampilkan pesan bahwa telah berhasil mengajukan cuti
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan pengalihan approval.",
        ]);

        //format tannggal
        $start_date = date('Y-m-d',strtotime($request->start_date));
        $end_date = date('Y-m-d',strtotime($request->end_date));
        
        //save
        $transition = new Transition();
        $transition->abbr_jobs = $request->abbr_jobs;
        $transition->personnel_no = $request->personnel_no;
        $transition->start_date = $start_date;
        $transition->end_date = $end_date;
        $transition->actived_at = date('Y-m-d H:i:s');
        $transition->save();

        // batalkan view create dan kembali ke parent
        return redirect()->route('transition.index');
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
    public function StructJab(Request $request)
    {
        return StructJab::getForSelect2($request);
    }

    public function employee(Request $request)
    {
        return StructDisp::getForSelect2($request);
    }
}
