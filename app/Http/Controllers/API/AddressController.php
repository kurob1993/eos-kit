<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SAP\Address;

class AddressController extends Controller
{
    public function show($PERNR,$ANSSA = 6)
    {
        // menampilkan informasi alamat adan no telp
        $employee =  Address::where('PERNR',$PERNR )
            ->where('ANSSA',$ANSSA)
            ->first();

        if (!is_null($employee))
            return $employee;
        else
            return []; 
    
    }
}
