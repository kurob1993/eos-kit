<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\SapSoapController;

class AbsenceQuotaSoapController extends Controller
{
    public function index()
    {
        $server = new \SoapServer(config('sapsoap.absence_quota.url'));
        $server->setClass( SapSoapController::class );
        $server->handle();
    }
}
