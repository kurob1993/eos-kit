<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\AllLeaveDataTable;
use App\Models\Stage;
use App\Models\Absence;

class AllLeaveController extends Controller
{

    public function index(AllLeaveDataTable $dataTable)
    {
        $stages = Stage::all();
        $foundYears = Absence::foundYear()->get();
        
        return $dataTable->render('all_leaves.index', 
            [ "stages" => $stages, "foundYears" => $foundYears ]
        );
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
