<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SAP\Zhrom0012;

class Zhrom0012Controller extends Controller
{
    public function index()
    {
        $namakompetensi = Zhrom0012::select('namakompetensi')
            ->groupBy('namakompetensi')
            ->get();
        return $namakompetensi;
    }

    public function nojabatan($nojabatan = null)
    {
        $zhrom0012 = Zhrom0012::where('nojabatan',$nojabatan)->get();
        $unique = $zhrom0012->unique('namakompetensi');
        return $unique->values()->all();
    }
}
