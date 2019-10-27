<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\StoreAbsenceRequest;
use Illuminate\Support\Facades\Crypt;
use App\Models\AttendanceQuota;
use App\Models\Ski;
use App\Models\SAP\Zhrom0013;
use App\Models\AbsenceApproval;

class DebugController extends Controller
{
    public function link($nik,$nama,$email)
    {
        echo '<a href="'.'http://dev.emss.com/a/'.$nik.'/'.$email.'"> '.$nama.' </a> </br>';
    }

    public function sec($nama,$email)
    {
        echo '<a href="'.'http://dev.emss.com/b/'.$email.'"> '.$nama.' </a> </br>';
    }
    public function debug()
    {   

        return Ski::with('stage')->get();
        
        // AttendanceQuota::where('secretary_id', '87')
        //     ->with([
        //         'overtimeReason', 
        //         'stage',
        //         'employee:personnel_no,name',
        //         'attendanceQuotaApproval'
        //         ]
        //     )->get();
    }
}
