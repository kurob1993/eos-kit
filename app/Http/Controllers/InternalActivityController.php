<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreInternalActivityRequest;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;

class InternalActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        // ambil data cuti untuk user tersebut
        $activity = Activity::ofLoggedUser()->where('type','internal')->get();

        // response untuk datatables absences
        if ($request->ajax()) {

            return Datatables::of($activity)
                ->editColumn('summary', function ($activity) {
                    // kolom summary menggunakan view _summary
                    return view('internal_activity._summary', [ 
                        'summary' => $activity,
                        'when' => $activity->created_at->format('d/m') 
                    ]);
                })
                ->editColumn('approver', function ($activity) {
                    $a = view('layouts._personnel-no-with-name', [
                        'personnel_no' => 'Admin',
                        'employee_name' => 'Personnel Services'
                        ]) . '<br />';
                    return $a;
                })
                ->setRowAttr([
                    // href untuk dipasang di setiap tr
                    'data-href' => function ($activity) {
                        return route('leaves.show', ['leaf' => $activity->id]);
                    } 
                ])
                ->escapeColumns([0,1])
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'paging' => true,
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

        // tampilkan view index dengan tambahan script html DataTables
        return view('internal_activity.index')->with(compact('html', 'activity'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('internal_activity.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInternalActivityRequest $request)
    {
        $activity = New Activity();
        $activity->personnel_no = Auth::user()->personnel_no;
        $activity->jenis_kegiatan = $request->jenis_kegiatan;
        $activity->posisi = $request->posisi;
        $activity->start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $activity->end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        $activity->keterangan = $request->keterangan;
        $activity->type = 'internal';
        $activity->stage_id = 1;
        if($activity->save()){
            return redirect()->route('internal-activity.index');
        }
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
