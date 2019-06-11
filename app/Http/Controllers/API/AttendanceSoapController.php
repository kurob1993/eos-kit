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
            'PERNR' => 2,
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
            $xml_string = $client->__getLastResponse();

            $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml_string);
            $xml=simplexml_load_string($response) or die("Error: Cannot create object");
            print_r($xml->SOAPBody->ns0MT_ATTENDANCE_RESPONSE->RESPONSE);  
        }
        catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function debug($data)
    {
        $myfile = fopen("../public/wsdl/response.xml", "w") or die("Unable to open file!");
        fwrite($myfile, $data);
        fclose($myfile);
    }

}
