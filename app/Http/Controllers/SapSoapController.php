<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsenceSapResponse;
use App\Models\Absence;
use function GuzzleHttp\json_encode;

class SapSoapController extends Controller
{
    public function SI_ABSENCE_QUOTA($data = array() )
    {
        foreach ($data->HCM_ABSENCE_QUOTA as $key => $value) {
            if($value->STATUS == 1){
                switch ($value->SUBTY) {
                    case '10':
                    case '20':
                        $this->cuti($value);
                        break;
                    default:
                        $this->izin($value);
                }
            }
            if($value->STATUS == 0){
                $this->gagal($value);
            }
        }
    }
    
    public function cuti($value)
    {
        $sap = new AbsenceSapResponse();
        $sap->reqno = $value->REQNO*1;
        $sap->pernr = $value->PERNR*1;
        $sap->status = $value->STATUS;
        $sap->subty = $value->SUBTY;
        $sap->ktart = $value->KTART;
        $sap->anzhl = $value->ANZHL*1;
        $sap->kverb = $value->KVERB*1;
        $sap->desta = date('Y-m-d', strtotime($value->DESTA) );
        $sap->deend = date('Y-m-d', strtotime($value->DEEND) );
        $sap->save();

        // $absence = Absence::find($value->REQNO*1);
        // $absence->stage_id = '3';
        // $absence->sendtosap_at = date('Y-d-m H:i:s');
        // $absence->save();

    }

    public function izin($value)
    {
        $sap = new AbsenceSapResponse();
        $sap->reqno = $value->REQNO*1;
        $sap->pernr = $value->PERNR*1;
        $sap->status = $value->STATUS;
        $sap->subty = $value->SUBTY;
        $sap->save();

        // $absence = Absence::find($value->REQNO*1);
        // $absence->stage_id = '3';
        // $absence->sendtosap_at = date('Y-d-m H:i:s');
        // $absence->save();
    }

    public function gagal($value)
    {
        $sap = new AbsenceSapResponse();
        $sap->reqno = $value->REQNO*1;
        $sap->pernr = $value->PERNR*1;
        $sap->status = $value->STATUS;
        $sap->subty = $value->SUBTY;
        $sap->desc = $value->DESC;
        $sap->save();
    }

    public function debug($data)
    {
        $myfile = fopen("../public/wsdl/response.json", "w") or die("Unable to open file!");
        fwrite($myfile, $data);
        fclose($myfile);
    }
}
