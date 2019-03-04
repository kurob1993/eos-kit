<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zhrom0012;

class Zhrom0012Controller extends Controller
{
    public function index(Request $request)
    {
        $namakompetensi = Zhrom0012::select('namakompetensi')
            ->groupBy('namakompetensi')
            ->get();
        return $namakompetensi;
    }

    public function nojabatan(Request $request, $nojabatan = null)
    {
        $zhrom0012 = Zhrom0012::where('nojabatan',$nojabatan)->get();
        $unique = $zhrom0012->unique('namakompetensi');
        return $unique->values()->all();
    }
}
