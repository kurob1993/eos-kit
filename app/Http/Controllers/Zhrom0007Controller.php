<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zhrom0007;

class Zhrom0007Controller extends Controller
{
    public function index(Request $request)
    {
        return Zhrom0007::all();
    }
    public function AbbrPosition(Request $request, $AbbrPosition = null)
    {
        return Zhrom0007::where('AbbrPosition',$AbbrPosition)->first();
    }
}
