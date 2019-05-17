<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsenceSapResponse;
use App\Models\Absence;
use App\Models\AbsenceQuota;
use function GuzzleHttp\json_encode;
use Carbon\Carbon;

class SapSoapController extends Controller
{
    public function SI_ABSENCE_QUOTA($data = array())
    {
        foreach ($data as $key => $value) {
            if($value->STATUS == 0){
                $this->gagal($value);
            }

            if($value->STATUS == 1){
                if($value->SUBTY == '10' || $value->SUBTY == '20'){
                    $this->cuti($value);
                }else{
                    $this->izin($value);
                }
            }

            if($value->STATUS == 2){
                $this->kuotaBaru($value);
            }
        }
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

        $mytime = Carbon::now();
        $absence = Absence::find($value->REQNO*1);
        $absence->sendtosap_at = $mytime->toDateTimeString();
        $absence->save();
    }

    public function cuti($value)
    {
        $reqno = $value->REQNO*1;
        $pernr = $value->PERNR*1;
        $desta = date('Y-m-d', strtotime($value->DESTA) );
        $deend = date('Y-m-d', strtotime($value->DEEND) );
        $begda = date('Y-m-d', strtotime($value->BEGDA) );
        $endda = date('Y-m-d', strtotime($value->ENDDA) );
        $number = $value->ANZHL*1;
        $deduction = $value->KVERB*1;

        // Menyimpan response
        $sap = new AbsenceSapResponse();
        $sap->reqno = $reqno;
        $sap->pernr = $pernr;
        $sap->status = $value->STATUS;
        $sap->subty = $value->SUBTY;
        $sap->ktart = $value->KTART;
        $sap->anzhl = $number;
        $sap->kverb = $deduction;
        $sap->desta = $desta;
        $sap->deend = $deend;
        $sap->begda = $begda;
        $sap->endda = $endda;
        $sap->save();

        $this->UpdateAbsenceQuota($value);
        $this->UpdateAbsence($reqno);
    }

    public function izin($value)
    {
        $sap = new AbsenceSapResponse();
        $sap->reqno = $value->REQNO*1;
        $sap->pernr = $value->PERNR*1;
        $sap->status = $value->STATUS;
        $sap->subty = $value->SUBTY;
        $sap->save();

        $this->UpdateAbsence($value->REQNO*1);
    }

    public function UpdateAbsence($reqno)
    {
        // Update table absence
        $mytime = Carbon::now();
        $absence = Absence::find($reqno);
        $absence->stage_id = '3';
        $absence->sendtosap_at = $mytime->toDateTimeString();
        $absence->save();
    }

    public function UpdateAbsenceQuota($value)
    {
        $pernr = $value->PERNR*1;
        $desta = date('Y-m-d', strtotime($value->DESTA) );
        $deend = date('Y-m-d', strtotime($value->DEEND) );
        $begda = date('Y-m-d', strtotime($value->BEGDA) );
        $endda = date('Y-m-d', strtotime($value->ENDDA) );
        $number = $value->ANZHL*1;
        $deduction = $value->KVERB*1;

        if($value->SUBTY == 10){
            $absenType = 1;
        }
        if($value->SUBTY == 20){
            $absenType = 2;
        }

        $AbsenceQuota = AbsenceQuota::where('personnel_no', $pernr)
        ->where('start_date',$begda)
        ->where('end_date',$endda);

        if($AbsenceQuota->count()){
            $AbsenceQuota = $AbsenceQuota->first();
        }else{
            $AbsenceQuota = new AbsenceQuota();
        }

        $AbsenceQuota->personnel_no = $pernr;
        $AbsenceQuota->start_date = $begda;
        $AbsenceQuota->end_date = $endda;
        $AbsenceQuota->absence_type_id = $absenType;
        $AbsenceQuota->start_deduction = $desta;
        $AbsenceQuota->end_deduction = $deend;
        $AbsenceQuota->number = $number;
        $AbsenceQuota->deduction = $deduction;
        $AbsenceQuota->save();
        
    }

    public function kuotaBaru($value)
    {
        $reqno = $value->REQNO*1;
        $pernr = $value->PERNR*1;
        $desta = date('Y-m-d', strtotime($value->DESTA) );
        $deend = date('Y-m-d', strtotime($value->DEEND) );
        $begda = date('Y-m-d', strtotime($value->BEGDA) );
        $endda = date('Y-m-d', strtotime($value->ENDDA) );
        $number = $value->ANZHL*1;
        $deduction = $value->KVERB*1;

        if($value->SUBTY == 10){
            $absenType = 1;
        }
        if($value->SUBTY == 20){
            $absenType = 2;
        }
        
        // Menyimpan response
        $sap = new AbsenceSapResponse();
        $sap->reqno = $reqno;
        $sap->pernr = $pernr;
        $sap->status = $value->STATUS;
        $sap->subty = $value->SUBTY;
        $sap->ktart = $value->KTART;
        $sap->anzhl = $number;
        $sap->kverb = $deduction;
        $sap->desta = $desta;
        $sap->deend = $deend;
        $sap->begda = $begda;
        $sap->endda = $endda;
        $sap->save();

        
        $count = AbsenceQuota::where('personnel_no',$pernr)
        ->where('start_date',$begda)
        ->where('end_date', $endda);

        // jika tidak ada data maka tambah
        // jika ada maka update
        if($count->count() == 0){
            $AbsenceQuota = new AbsenceQuota();
        }else{
            $AbsenceQuota = $count->first();
        }

        $AbsenceQuota->personnel_no = $pernr;
        $AbsenceQuota->start_date = $begda;
        $AbsenceQuota->end_date = $endda;
        $AbsenceQuota->absence_type_id = $absenType;
        $AbsenceQuota->start_deduction = $desta;
        $AbsenceQuota->end_deduction = $deend;
        $AbsenceQuota->number = $number;
        $AbsenceQuota->deduction = $deduction;
        $AbsenceQuota->save();
    }

    public function debug($data)
    {
        $myfile = fopen("../public/wsdl/response.json", "w") or die("Unable to open file!");
        fwrite($myfile, $data);
        fclose($myfile);
    }
}
