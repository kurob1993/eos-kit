<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsenceSoapController extends Controller
{
    public function index()
    {
        $data= (object) array(
            'HCM_ABSENCE' => array(
                'REQNO'=> '2',
                'PERNR'=> '11725',
                'SUBTY'=> '2001',
                'SUBTY_TEXT'=> 'absence',
                'ENDDA'=> '02.05.2019',
                'BEGDA'=> '26.04.2019'
            )
        );

        $url = '../public/wsdl/SI_ABSENCE.WSDL';
        $options = array(
            'login' => 'SAPWEBAPP',
            'password' => '1234567',
            'soap_version' => SOAP_1_1, 
            'trace' => 1,
            'exceptions' => 0
        );

        try {
            $client = new \SoapClient($url, $options); 
            $res    = $client->SI_ABSENCE($data);
        }
        catch(Exception $e) {
            die($e->getMessage());
        }

        var_dump($res);
        die;
    }
}
