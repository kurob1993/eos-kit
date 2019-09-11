<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\StoreAbsenceRequest;
use Illuminate\Support\Facades\Crypt;
use App\Models\AttendanceQuota;
use App\Models\SAP\Zhrom0013;
use App\Models\AbsenceApproval;
use App\Jobs\SendNotifToSso;
use App\Message;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;

class DebugController extends Controller
{
    public function link($nik, $nama, $email)
    {
        echo '<a href="'.'http://dev.emss.com/a/'.$nik.'/'.$email.'"> '.$nama.' </a> </br>';
    }

    public function sec($nama, $email)
    {
        echo '<a href="'.'http://dev.emss.com/b/'.$email.'"> '.$nama.' </a> </br>';
    }
    public function debug()
    {
        $nik = '1993';
        $title = 'Cuti 18181';
        $body = 'Selamat Cuti Anda Berhasil dibuat.';
        $url = url('notif-sso/absence/18181');
        SendNotifToSso::dispatch($nik,$title,$body,$url);
    }
}
