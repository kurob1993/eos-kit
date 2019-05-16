<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Http\Requests\StoreTimeEventRequest;
use App\Models\TimeEvent;
use App\Models\TimeEventType;

class TimeEventController extends Controller
{

    public function index(Request $request, Builder $htmlBuilder)
    {
        // ambil data tidak slash dari timeEvents untuk user tersebut
        $timeEvents = TimeEvent::ofLoggedUser()
            ->with(['timeEventType', 'stage'])
            ->get();

            // response untuk datatables timeEvents
        if ($request->ajax()) {

            return Datatables::of($timeEvents)
                ->editColumn('summary', function ($timeEvent) {
                    // kolom summary menggunakan view _summary
                    return view('time_events._summary', [ 
                        'summary' => $timeEvent,
                        'when' => $timeEvent->created_at->format('d/m') 
                    ]);
                })
                ->editColumn('approver', function ($timeEvent) {
                    // personnel_no dan name atasan
                    return view('layouts._personnel-no-with-name', [
                        'personnel_no' => $timeEvent->timeEventApprovals->first()->user['personnel_no'],
                        'employee_name' => $timeEvent->timeEventApprovals->first()->user['name'],
                    ]);
                })
                ->setRowAttr([
                    // href untuk dipasang di setiap tr
                    'data-href' => function ($timeEvent) {
                        return route('time_events.show', ['time_event' => $timeEvent->id]);
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

        // tampilkan view index dengan tambahan script html DataTables
        return view('time_events.index')->with(compact('html', 'timeEvents'));
    }

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
            return redirect()->route('time_events.index');
        }
        
        // transform array to key value pairs. For alternative:
        // $data = array_map(function($obj){ return (array) $obj; }, $ref);
        $timeEventType = TimeEventType::all('id', 'description')
            ->mapWithKeys(function ($item) {
                return [$item['id'] => $item['description']];
            })
            ->all();

        // tampilkan view create
        return view('time_events.create', [ 
            'can_delegate' => $canDelegate, 
            'permit_types' => $timeEventType,
        ]);
    }

    public function store(StoreTimeEventRequest $request)
    {
        // tampilkan pesan bahwa telah berhasil mengajukan tidak slash
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan pengajuan tidak slash.",
        ]);

        // membuat pengajuan tidak slash dengan menambahkan data personnel_no
        $timeEvent = TimeEvent::create($request->all()
             + ['personnel_no' => Auth::user()->personnel_no]);

        return redirect()->route('time_events.index');
    }

    public function show($id)
    {
        $timeEvent = TimeEvent::find($id)
            ->load(['timeEventType', 'stage', 'timeEventApprovals']);

        $timeEventId = $timeEvent->id;
        
        return view('time_events.show', compact('timeEvent', 'timeEventId'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
