<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use App\Models\Absence;
use App\Models\Attendance;
use App\Models\AttendanceQuota;
use App\Models\TimeEvent;
use App\Models\Stage;

class PersonnelServiceController extends Controller
{
    private function switchSubmission($approval, $id)
    {
        switch($approval) {
            case 'absence':
                $submission = Absence::find($id);
            break;
            case 'attendance':
                $submission = Attendance::find($id);
            break;
            case 'time_event':
                $submission = TimeEvent::find($id);
            break;
            case 'overtime':
                $submission = AttendanceQuota::find($id);
            break;
        }

        return $submission;
    }

    public function integrate(Request $request, $approval, $id)
    {
        $submission = $this->switchSubmission($approval, $id);
        
        // dispatch event agar dapat dimasukkan ke dalam job
        event(new SendingAbsenceToSap($submission));
        
        // tampilkan pesan bahwa telah berhasil 
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Integrasi sedang dilakukan. Silahkan cek email."
        ]);

        // kembali lagi ke index
        return redirect()->back();
    }

    public function confirm(Request $request, $approval, $id)
    {
        $submission = $this->switchSubmission($approval, $id);
        $submission->stage_id = Stage::successStage()->id;
        
        if (!$submission->save()) {
            // kembali lagi jika gagal
            return redirect()->back();
        }

        // tampilkan pesan bahwa telah berhasil menolak
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Data cuti berhasil dikonfirmasi masuk ke SAP."
        ]);

        // kembali lagi ke all leaves
        return redirect()->back();
    }
}
