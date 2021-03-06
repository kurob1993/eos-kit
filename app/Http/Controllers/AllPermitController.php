<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\AllAbsencePermitDataTable;
use App\DataTables\AllAttendancePermitDataTable;
use App\Models\Stage;
use App\Models\Absence;
use App\Models\Attendance;

class AllPermitController extends Controller
{

    public function absence(AllAbsencePermitDataTable $dataTable)
    {
        $stages = Stage::all();
        $foundYears = Absence::foundYear()->get();
        return $dataTable->render('all_permits.absence.index', 
            [ "stages" => $stages, "foundYears" => $foundYears ]);       
    }

    public function attendance(AllAttendancePermitDataTable $dataTable)
    {
        $stages = Stage::all();
        $foundYears = Attendance::foundYear()->get();
        return $dataTable->render('all_permits.attendance.index', 
        [ "stages" => $stages, "foundYears" => $foundYears ]);   
    }

    public function index()
    {
        //
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
