<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SAP\OrgText;
use App\Models\SAP\StructDisp;

class OrganizationController extends Controller
{

    public function index()
    {
        $paginated = OrgText::lastOrg()->get();

        return $paginated;
    }

    public function show($ObjectID, $date = null)
    {
        if (is_null($date)) {
            return OrgText::findByObjectID($ObjectID)
                ->lastOrg()
                ->first();
        } else {
            return OrgText::findByCompositeKey($ObjectID, $date)
                ->first();
        }
    }
    
    public function showAbbr($Objectabbr = null, $date = null)
    {
        if (is_null($date) && is_null($Objectabbr)) {
            return OrgText::lastOrg()->get();
        }

        if($Objectabbr && is_null($date) ){
            return OrgText::findByObjectabbr($Objectabbr)
            ->get();
        }

        if($Objectabbr && $date){
            return OrgText::findByCompositeKey($Objectabbr, $date)
                ->first();
        }
    }

    public function unitkerja($unitkerja = null, $date = null)
    {
       return OrgText::lastUk($unitkerja, $date)->get();       
    }

    public function unitkerjaold($unitkerja = null)
    {
       return OrgText::oldDiv($unitkerja)->get();       
    }

    public function boss($emporid = null)
    {
       return StructDisp::orgBoss($emporid)->get();       
    }

}
