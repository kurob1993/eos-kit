<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SAP\It002;

class It002Controller extends Controller
{
    protected function findByPersonnel($PERNR)
    {
        // menampilkan informasi karyawan
        return It002::findByPersonnel($PERNR)
            ->first();
    }

    public function index()
    {
        $paginated = It002::selfStruct()->paginate();
        $paginated->transform(function ($item, $key) {
            return It002::findByPersonnel($item->PERNR)->first();
        });

        return $paginated;
    }

    public function show($PERNR)
    {
        // menampilkan informasi karyawan
        $employee =  It002::where('PERNR',$PERNR)->where('ENDDA','9999-12-31')->first();

        if (!is_null($employee))
            return $employee;
        else
            return []; 
    
    }
}
