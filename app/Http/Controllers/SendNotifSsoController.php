<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absence;

class SendNotifSsoController extends Controller
{
    public function absence($id)
    {
        $absence = Absence::find($id);
        $type = $absence->absenceType()->first()->subtype;
        if($type == '0100' || $type == '0200'){
            $absenceType = 'Cuti';
        }else{
            $absenceType = 'Izin';
        }
       return view('notif_sso.absence', compact('absence','absenceType') );
    }
}
