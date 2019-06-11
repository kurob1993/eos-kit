<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceSoapController extends Controller
{
    public function index()
    {
        // $absence = Absence::where('stage_id','2')
        // ->where('sendtosap_at', null)
        // ->limit(1);
        
        // if($absence->count() == 0){
        //     dd('Tidak Ada Data');
        // }

        $data = array(
            'REQNO' => 1,
            'PERNR' => 1,
            'SUBTY_TEXT' => 'Training (internal)',
            'SUBTY' => '0110',
            'ENDDA' => '20190501',
            'BEGDA' => '20190501'
        );

        // foreach ($absence->get() as $key => $value) {
        //     $ab = array(
        //         'REQNO'=> "$value->id",
        //         'PERNR'=> "$value->personnel_no",
        //         'SUBTY'=> $value->absenceType->subtype,
        //         'SUBTY_TEXT'=> $value->absenceType->text,
        //         'ENDDA'=> $value->end_date->format('Ymd'),
        //         'BEGDA'=> $value->start_date->format('Ymd')
        //     );
        //     array_push($data,$ab);
        // }

        $data = (object) array(
            'HCM_ATTENDANCE' => $data
        );

        $url = config('sapsoap.attendance.url');
        $options = array(
            'login' => 'SAPWEBAPP',
            'password' => '1234567',
            'soap_version' => SOAP_1_1, 
            'trace' => 1,
            'exceptions' => 0
        );

        try {
            $client = new \SoapClient($url, $options); 
            $client->SI_ATTENDANCE($data);

            $xml_string = htmlentities($client->__getLastResponse());
            // $xml = simplexml_load_string($xml_string);
            // $json = json_encode($xml);
            // $array = json_decode($json,TRUE);
            // $xml_string = htmlentities($client->__getLastResponse());
            $xmlObject = simplexml_load_string($xml_string);
            echo $xml_string;
        }
        catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
