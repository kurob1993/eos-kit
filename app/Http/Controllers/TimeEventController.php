<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Config;
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
        // response untuk datatables timeEvents
        if ($request->ajax()) {

            // ambil data tidak slash dari timeEvents untuk user tersebut
            $timeEvents = TimeEvent::where('personnel_no', Auth::user()->personnel_no)
                ->with(['timeEventType', 'stage'])
                ->get();

            // mengembalikan data sesuai dengan format yang dibutuhkan DataTables
            return Datatables::of($timeEvents)
                ->editColumn('stage.description', function (TimeEvent $timeEvent) {
                    return '<span class="label label-default">' 
                    . $timeEvent->stage->description . '</span>'; })
                ->editColumn('check_date', function (TimeEvent $timeEvent) {
                    return $timeEvent->check_date->format(config('emss.date_format')); })
                ->editColumn('check_time', function (TimeEvent $timeEvent) {
                    return $timeEvent->check_time; })
                ->escapeColumns([4])
                ->make(true);
        }

        // html builder untuk menampilkan kolom di datatables
        $html = $htmlBuilder
            ->addColumn([
                'data' => 'id',
                'name' => 'id', 
                'title' => 'ID'
                ])
            ->addColumn([
                'data' => 'check_date', 
                'name' => 'check_date', 
                'title' => 'Tanggal'
                ])
            ->addColumn([
                'data' => 'check_time', 
                'name' => 'check_time', 
                'title' => 'Jam'
                ])
            ->addColumn([
                'data' => 'time_event_type.description', 
                'name' => 'time_event_type.description', 
                'title' => 'Jenis', 
                'searchable' => false
                ])
            ->addColumn([
                'data' => 'stage.description', 
                'name' => 'stage.description', 
                'title' => 'Tahap', 
                'searchable' => false
                ]);

        // tampilkan view index dengan tambahan script html DataTables
        return view('time_events.index')->with(compact('html'));
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
        //
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
