<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SapSoapController extends Controller
{
    public function SI_ABSENCE_QUOTA($data = array() )
    {
        $txt = json_encode($data);
        $this->debug($txt);
        // foreach ($data as $key => $value) {
        //     // $sap = new SapAbsenceQuota();
        //     // $sap->REQNO = $value->REQNO;
        //     // $sap->PERNR = $value->PERNR;
        //     // $sap->STATUS = $value->STATUS;
        //     // $sap->SUBTY = $value->SUBTY;
        //     // $sap->KTART = $value->KTART;
        //     // $sap->ANZHL = $value->ANZHL;
        //     // $sap->KVERB = $value->KVERB;
        //     // $sap->DESTA = $value->DESTA;
        //     // $sap->DEEND = $value->DEEND;
        //     // $sap->DESC = $value->DESC;
        //     // $sap->save();
        //     $this->debug(json_encode($value) );
        // }
    }
    
    public function debug($data)
    {
        $myfile = fopen("../public/wsdl/respont.json", "w") or die("Unable to open file!");
        fwrite($myfile, $data);
        fclose($myfile);
    }
}
