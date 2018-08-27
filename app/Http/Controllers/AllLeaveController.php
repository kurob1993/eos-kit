<?php

namespace App\Http\Controllers;

use Illuminate\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Session;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Http\Requests\StoreAbsenceRequest;
use App\Models\Absence;
use App\Models\Stage;
use App\Events\SendingAbsenceToSap;

class AllLeaveController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        // response untuk datatables absences
        if ($request->ajax()) {

            // ambil semua data cuti user
            $absences = Absence::with(['absenceType', 'stage']);

            // mengembalikan data sesuai dengan format yang dibutuhkan DataTables
            return Datatables::of($absences)
                ->editColumn('stage.description', function (Absence $absence) {
                    return '<span class="label label-default">' 
                    . $absence->stage->description . '</span>';
                })
                ->editColumn('start_date', function (Absence $absence) {
                    return $absence->start_date->format(config('emss.date_format'));
                })
                ->editColumn('end_date', function (Absence $absence) {
                    return $absence->end_date->format(config('emss.date_format'));
                })
                ->editColumn('action', function (Absence $absence) {
                    // apakah stage-nya finished OR denied kemudian biarkanlah
                    if ($absence->isFinished) {
                        return '<span class="label label-primary">Finished</span>';
                    } else if ($absence->isDenied) {
                        return '<span class="label label-primary">Denied</span>';
                    // apakah stage-nya: sent to sap kemudian coba kirim manual
                    // atau dikirim secara otomatis (belum diakomodasi)
                    } else if ($absence->isSentToSap) {
                        return view('all_leaves._action', [
                            'model' => $absence,
                            'integrate_url' => route('all_leaves.integrate', $absence->id),
                            'confirm_url' => route('all_leaves.confirm', $absence->id)
                        ]);
                    // apakah stage-nya: failed
                    } else if ($absence->isFailed) {
                        return '<span class="label label-primary">Failed</span>';
                    }
                })                    
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
                'data' => 'personnel_no', 
                'name' => 'personnel_no', 
                'title' => 'NIK'
                ])
            ->addColumn([
                'data' => 'start_date', 
                'name' => 'start_date', 
                'title' => 'Mulai'
                ])
            ->addColumn([
                'data' => 'end_date',
                'name' => 'end_date',
                'title' => 'Berakhir'
                ])
            ->addColumn([
                'data' => 'absence_type.text',
                'name' => 'absence_type.text',
                'title' => 'Jenis',
                'searchable' => false
                ])
            ->addColumn([
                'data' => 'stage.description', 
                'name' => 'stage.description', 
                'title' => 'Tahap', 
                'searchable' => false
                ])
            ->addColumn([
                'data' => 'action',
                'name' => 'action',
                'title' => '',
               'searchable' => false,
                'orderable' => false
            ]);

        // tampilkan view index dengan tambahan script html DataTables
        return view('all_leaves.index')->with(compact('html'));
    }

    public function create()
    {

    }

    public function store(StoreAbsenceRequest $request)
    {

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

    public function integrate(Request $request, $id)
    {
        // cari berdasarkan id kemudian update berdasarkan request + status reject
        $absence = Absence::find($id);
        
        // dispatch event agar dapat dimasukkan ke dalam job
        event(new SendingAbsenceToSap($absence));
        
        // tampilkan pesan bahwa telah berhasil 
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Integrasi sedang dilakukan. Silahkan cek email."
        ]);

        // kembali lagi ke index
        return redirect()->route('all_leaves.index');
    }

    public function confirm(Request $request, $id)
    {
        // cari berdasarkan id kemudian update berdasarkan request + status reject
        $absence = Absence::find($id);
        $absence->stage_id = Stage::successStage()->id;
        
        if (!$absence->save()) {
            // kembali lagi jika gagal
            return redirect()->back();
        }

        // tampilkan pesan bahwa telah berhasil menolak
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Data cuti berhasil dikonfirmasi masuk ke SAP."
        ]);

        // kembali lagi ke all leaves
        return redirect()->route('all_leaves.index');
    }    
}
