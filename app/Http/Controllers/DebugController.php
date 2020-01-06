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
use App\Models\SAP\StructDisp;
use App\Models\AbsenceApproval;
use App\Models\InternalActivity;

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

        $sturctDisp = Auth::user()->employee->minSptBossWithDelegation()->personnel_no;
        // $sturctDisp = $sturctDisp->map(function($item, $key){
        //     return StructDisp::where('empkostl',$item->empkostl)
        //         ->where('no',1)
        //         ->where('empnik','<>',$item->empnik)
        //         ->where('emppersk','like','%'.$item->emppersk[0].'%')
        //         ->get();
        // })->first();
        
        return $sturctDisp;
    }
}
