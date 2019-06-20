<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AttendanceSapResponses;
use App\Models\Attendance;

class AttendanceSoapController extends Controller
{
    public function index()
    {
        $attendance = Attendance::where('stage_id','2')
        ->where('sendtosap_at', null)
        ->limit(1);
        
        if($attendance->count() == 0){
            return ['response' => 'tidak ada data'];
        }

        $data = [];
        foreach ($attendance->get() as $key => $value) {
            $ab = array(
                'REQNO'=> "$value->id",
                'PERNR'=> "$value->personnel_no",
                'SUBTY'=> $value->attendanceType->subtype,
                'SUBTY_TEXT'=> $value->attendanceType->text,
                'ENDDA'=> $value->end_date->format('Ymd'),
                'BEGDA'=> $value->start_date->format('Ymd')
            );
            array_push($data,$ab);
        }

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
            $RESPONSE = $xml->SOAPBody->ns0MT_ATTENDANCE_RESPONSE->RESPONSE;

            if($RESPONSE->STATUS == 1){
                $this->success($RESPONSE);
            }else{
                $this->error($RESPONSE);
            }
        }
        catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function success($response)
    {
        $asr = new AttendanceSapResponses();
        $asr->reqno = $response->REQNO*1;
        $asr->pernr = $response->PERNR*1;
        $asr->status = $response->STATUS;
        $asr->save();
        
        if($asr){
            $att = Attendance::find($response->REQNO*1);
            $att->stage_id = 3;
            $att->sendtosap_at = date('Y-m-d H:i:s');
            $att->save();
        }
    }

    public function error($response)
    {
        $asr = new AttendanceSapResponses();
        $asr->reqno = $response->REQNO*1;
        $asr->pernr = $response->PERNR*1;
        $asr->status = $response->STATUS;
        $asr->desc = $response->DESC;
        $asr->save();

        if($asr){
            $att = Attendance::find($response->REQNO*1);
            $att->sendtosap_at = date('Y-m-d H:i:s');
            $att->save();
        }
    }

    public function debug($data)
    {
        $myfile = fopen("../public/wsdl/response.xml", "w") or die("Unable to open file!");
        fwrite($myfile, $data);
        fclose($myfile);
    }

}
