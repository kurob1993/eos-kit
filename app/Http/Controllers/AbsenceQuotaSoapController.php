<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SapSoapController;

class AbsenceQuotaSoapController extends Controller
{
    public function index()
    {
        $server = new \SoapServer('../public/wsdl/SI_ABSENCE_QUOTA.WSDL');
        $server->setClass( SapSoapController::class );
        $server->handle();
    }
}
