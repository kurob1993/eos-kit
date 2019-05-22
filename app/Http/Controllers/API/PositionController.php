<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;

class PositionController extends Controller
{
    protected function findByPersonnel($personnel_no)
    {
        return Employee::findByPersonnel($personnel_no)
            ->with('positions:PERNR,BEGDA,ENDDA,PERSG,T501T_PTEXT,PERSK,T503T_PTEXT,KOSTL,ORGEH,HRP1000_O_SHORT,HRP1000_O_STEXT,PLANS,HRP1000_S_SHORT,HRP1000_S_STEXT');
    }

    protected function findPositionByPersonnel($personnel_no)
    {
        return $this->findByPersonnel($personnel_no)
            ->first()
            ->positions;
    }

    public function show($personnel_no)
    {
        return $this->findPositionByPersonnel($personnel_no);
    }

    public function last($personnel_no)
    {
        $positions = $this->findPositionByPersonnel($personnel_no)->last();
        
        return $positions;
    }
}
