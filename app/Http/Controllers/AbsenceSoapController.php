<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absence;

class AbsenceSoapController extends Controller
{
    public function index()
    {        
        
        $absence = Absence::where('stage_id','2')
        ->where('sendtosap_at', null)
        ->limit(1)->get();
        
        $data = array();
        foreach ($absence as $key => $value) {
            $ab = array(
                'REQNO'=> "$value->id",
                'PERNR'=> "$value->personnel_no",
                'SUBTY'=> $value->absenceType->subtype,
                'SUBTY_TEXT'=> $value->absenceType->text,
                'ENDDA'=> $value->end_date->format('Ymd'),
                'BEGDA'=> $value->start_date->format('Ymd')
            );
            array_push($data,$ab);
        }

        $data = (object) array(
            'HCM_ABSENCE' => $data
        );

        $url = config('sapsoap.absence.url');
        $options = array(
            'login' => 'SAPWEBAPP',
            'password' => '1234567',
            'soap_version' => SOAP_1_1, 
            'trace' => 1,
            'exceptions' => 0
        );

        try {
            $client = new \SoapClient($url, $options); 
            $client->SI_ABSENCE($data);
        }
        catch(Exception $e) {
            die($e->getMessage());
        }

    }
}
