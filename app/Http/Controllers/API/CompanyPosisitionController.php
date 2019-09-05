<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyPosisition;

class CompanyPosisitionController extends Controller
{
    public function AbbrPosition($idcompany = null)
    {
        return CompanyPosisition::where('company_id',$idcompany) 
            ->get(['AbbrPosition','NameofPosition','LvlOrg']);
    }
}
