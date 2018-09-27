<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\AllLeaveDataTable;

class AllLeaveController extends Controller
{

    public function index(AllLeaveDataTable $dataTable)
    {
        return $dataTable->render('all_leaves.index');
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
