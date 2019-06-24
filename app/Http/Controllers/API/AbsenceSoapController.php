<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absence;

class AbsenceSoapController extends Controller
{
    public function index()
    {        
        
        $absence = Absence::where('stage_id','2')
        ->where('sendtosap_at', null)
        ->limit(1);
        
        if($absence->count() == 0){
            return ['response' => 'Tidak Ada Data'];
        }

        $data = array();
        foreach ($absence->get() as $key => $value) {
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

        $context = stream_context_create([
            'ssl' => [
                // set some SSL/TLS specific options
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);

        $url = asset('wsdl/SI_ABSENCE_PROD.WSDL');
        $options = array(
            'login' => 'SAPWEBAPP',
            'password' => '1234567',
            'soap_version' => SOAP_1_1, 
            'trace' => 1,
            'exceptions' => 0,
            'stream_context' => $context
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
