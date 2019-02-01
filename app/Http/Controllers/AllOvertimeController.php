<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\AllOvertimeDataTable;
use App\Models\Stage;
use App\Models\AttendanceQuota;

class AllOvertimeController extends Controller
{

    public function index(AllOvertimeDataTable $dataTable)
    {
        $stages = Stage::all();
        $foundYears = AttendanceQuota::foundYear()->get();

        return $dataTable->render('all_overtimes.index', 
            [ "stages" => $stages, "foundYears" => $foundYears ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
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
