<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\SecOvertimeDataTable;

class SecretaryController extends Controller
{
    public function index()
    {
        return view('dashboards.secretary');
    }

    public function leave()
    {
        return view('secretary.leaves.index');
    }

    public function permit()
    {
        return view('secretary.permits.index');
    }

    public function overtime(SecOvertimeDataTable $dataTable)
    {
        return $dataTable->render('secretary.overtimes.index');
    }

    public function time_event()
    {
        return view('secretary.time_events.index');
    }

    public function createOvertime()
    {
        
    }

    public function store(Request $request)
    {
        //
    }
}
