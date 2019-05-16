<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SAP\Zhrom0007;

class Zhrom0007Controller extends Controller
{
    public function index()
    {
        return Zhrom0007::all();
    }
    public function AbbrPosition($AbbrPosition = null)
    {
        return Zhrom0007::where('AbbrPosition',$AbbrPosition)->first();
    }
}
