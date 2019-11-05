<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\ExternalActivity;
use App\Models\ExternalActivityOrganization;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreExternalActivityRequest;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;

class ExternalActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        // ambil data cuti untuk user tersebut
        $activity = ExternalActivity::ofLoggedUser()->get();

        // dd($activity);
        // response untuk datatables absences
        if ($request->ajax()) {

            return Datatables::of($activity)
                ->editColumn('summary', function ($activity) {
                    // kolom summary menggunakan view _summary
                    return view('external_activity._summary', [ 
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
            'ordering'=> true,
            'sDom' => 'tpi',
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
        return view('external_activity.index')->with(compact('html', 'activity'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organisasi = ExternalActivityOrganization::all();
        return view('external_activity.create', compact('organisasi'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExternalActivityRequest $request)
    {
        $activity = New ExternalActivity();
        $activity->personnel_no = Auth::user()->personnel_no;
        $activity->external_activity_organization_id = $request->organisasi;
        $activity->posisi = $request->posisi;
        $activity->start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $activity->end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        // $activity->keterangan = $request->keterangan;
        $activity->nama_organisasi = $request->nama_organisasi;
        $activity->type = 'external';
        $activity->stage_id = 1;
        if($activity->save()){
            return redirect()->route('external-activity.index');
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
