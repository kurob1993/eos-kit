<?php

namespace App\Zoap\AbsenceQuota;

use SoapFault;
use App\Zoap\AbsenceQuota\AbsenceQuotaProvider as Provider;

/**
 * An example of a class that is used as a SOAP gateway to application functions.
 */
class AbsenceQuotaService
{
    /*
    |--------------------------------------------------------------------------
    | Public Methods
    |--------------------------------------------------------------------------
    */
    
    public function MT_ABSENCE_QUOTA($HCM_ABSENCE_QUOTA = ['REQNO' => ''])
    {
        // <REQNO>?</REQNO>
        // <PERNR>?</PERNR>
        // <STATUS>?</STATUS>
        // <SUBTY>?</SUBTY> 10 = cuti tahunan 20  
        // <KTART>?</KTART> 10 = cuti tahunan 20
        // <ANZHL>?</ANZHL> number quota
        // <KVERB>?</KVERB> deduction
        // <DESTA>?</DESTA> start
        // <DEEND>?</DEEND> end
        // <DESC>?</DESC>
        // throw new SoapFault('SOAP-ENV:Client', 'Belum diimplementasi');
        return Provider::updateDeduction($productId);
    }

    /*
    |--------------------------------------------------------------------------
    | Utility
    |--------------------------------------------------------------------------
    */
    
    /**
     * Convert array of KeyValue objects to associative array, non-recursively.
     *
     * @param \Viewflex\Zoap\Demo\Types\KeyValue[] $objects
     * @return array
     */
    protected static function arrayOfKeyValueToArray($objects)
    {
        $return = array();
        foreach ($objects as $object) {
            $return[$object->key] = $object->value;
        }

        return $return;
    }

}
