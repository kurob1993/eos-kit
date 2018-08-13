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
use App\Models\AbsenceQuota;

class AllLeaveController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        // response untuk datatables absences
        if ($request->ajax()) {

            // ambil data cuti untuk user tersebut
            $absences = Absence::with(['absenceType', 'stage']);

            // mengembalikan data sesuai dengan format yang dibutuhkan DataTables
            return Datatables::of($absences)
                ->editColumn('stage.description', function (Absence $absence) {
                    return '<span class="label label-default">' 
                    . $absence->stage->description . '</span>';})
                ->editColumn('start_date', function (Absence $absence) {
                    return $absence->start_date->format(config('emss.date_format'));})
                ->editColumn('end_date', function (Absence $absence) {
                    return $absence->end_date->format(config('emss.date_format'));})
                ->escapeColumns([4])
                ->make(true);
        }

        // html builder untuk menampilkan kolom di datatables
        $html = $htmlBuilder
            ->addColumn(['data' => 'id', 'name' => 'id', 'title' => 'ID'])
            ->addColumn(['data' => 'personnel_no', 'name' => 'personnel_no', 'title' => 'NIK'])
            ->addColumn(['data' => 'start_date', 'name' => 'start_date', 'title' => 'Mulai'])
            ->addColumn(['data' => 'end_date', 'name' => 'end_date', 'title' => 'Berakhir'])
            ->addColumn(['data' => 'absence_type.text', 'name' => 'absence_type.text', 
                'title' => 'Jenis', 'searchable' => false])
            ->addColumn(['data' => 'stage.description', 'name' => 'stage.description', 
                'title' => 'Tahap', 'searchable' => false]);

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
}
