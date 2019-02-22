<?php

namespace App\Http\Controllers;
use App\Models\Zhrom0013;

use Illuminate\Http\Request;

class Zhrom0013Controller extends Controller
{
    public function index()
    {
        # code...
    }
    public function nojabatan(Request $request, $nojabatan = null)
    {
        return Zhrom0013::where('nojabatan',$nojabatan)->get();
    }
}
