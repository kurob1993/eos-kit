<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\SapSoapController;

class AbsenceQuotaSoapController extends Controller
{
    public function index()
    {
        $url = asset('wsdl/SI_ABSENCE_QUOTA_PROD.WSDL');
        $server = new \SoapServer($url);
        $server->setClass( SapSoapController::class );
        $server->handle();
    }
}
