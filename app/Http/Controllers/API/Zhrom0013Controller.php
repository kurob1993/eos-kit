<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SAP\Zhrom0013;

class Zhrom0013Controller extends Controller
{
    public function nojabatan($nojabatan = null)
    {
        return Zhrom0013::where('nojabatan',$nojabatan)->get();
    }
}
