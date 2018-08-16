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

class AllAbsenceQuotaController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        // response untuk datatables absences
        if ($request->ajax()) {

            // ambil data cuti untuk user tersebut
            $absenceQuotas = AbsenceQuota::with(['absenceType']);

            // mengembalikan data sesuai dengan format yang dibutuhkan DataTables
            return Datatables::of($absenceQuotas)
                ->editColumn('start_date', function (AbsenceQuota $absenceQuotas) {
                    return $absenceQuotas->start_date->format(config('emss.date_format'));})
                ->editColumn('end_date', function (AbsenceQuota $absenceQuotas) {
                    return $absenceQuotas->end_date->format(config('emss.date_format'));})
                ->make(true);
        }

        // html builder untuk menampilkan kolom di datatables
        $html = $htmlBuilder
            ->addColumn(['data' => 'personnel_no', 'name' => 'personnel_no', 'title' => 'NIK'])
            ->addColumn(['data' => 'start_date', 'name' => 'start_date', 'title' => 'Mulai'])
            ->addColumn(['data' => 'end_date', 'name' => 'end_date', 'title' => 'Berakhir'])
            ->addColumn(['data' => 'absence_type.text', 'name' => 'absence_type.text', 
            'title' => 'Jenis', 'searchable' => false])
            ->addColumn(['data' => 'number', 'name' => 'number', 'title' => 'Jatah Cuti'])
            ->addColumn(['data' => 'deduction', 'name' => 'deduction', 'title' => 'Cuti terpakai']);
            
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
