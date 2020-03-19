<?php

namespace App\Http\Controllers;

use App\DataTables\AllAbsenceQuotaDataTable;

class AllAbsenceQuotaController extends Controller
{
    public function index(AllAbsenceQuotaDataTable $dataTable)
    {
        return $dataTable->render('all_absence_quotas.index');
    }

    public function create()
    {
        return view('all_absence_quotas.create');
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
