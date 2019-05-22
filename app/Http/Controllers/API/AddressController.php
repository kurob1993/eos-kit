<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SAP\Address;

class AddressController extends Controller
{
    public function show($PERNR)
    {
        // menampilkan informasi alamat adan no telp
        $employee =  Address::where('PERNR',$PERNR )->get();

        if (!is_null($employee))
            return $employee;
        else
            return []; 
    
    }
}
