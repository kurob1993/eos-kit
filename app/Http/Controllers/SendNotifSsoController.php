<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absence;
use App\Models\absenceApproval;

class SendNotifSsoController extends Controller
{
    public function absence($id)
    {
        $absence = Absence::find($id);
        $type = $absence->absenceType()->first()->subtype;
        if($absence->is_a_leave){
            $absenceType = 'Cuti';
        }else{
            $absenceType = 'Izin';
        }
       return view('notif_sso.absence', compact('absence','absenceType') );
    }

    public function absenceApproval($id)
    {
        $absenceApproval = absenceApproval::find($id);
        $absence = $absenceApproval->absence;
        $type = $absence->absenceType()->first()->subtype;
        if($absence->is_a_leave){
            $absenceType = 'Cuti';
        }else{
            $absenceType = 'Izin';
        }
        return view('notif_sso.absenceApproval', compact('absence','absenceType','absenceApproval') );
    }

}
