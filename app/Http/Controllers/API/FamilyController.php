<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;

class FamilyController extends Controller
{
    protected function findByPersonnel($personnel_no)
    {
        return Employee::findByPersonnel($personnel_no)
            ->with('families:PERNR,FAMSA,T591S_STEXT,FCNAM,FGBDT,FGBOT,FASEX_DESC,KDZUG as REIMBURSEMENT')
            ->first();
    }

    protected function findFamilyByPersonnel($personnel_no)
    {
        return $this->findByPersonnel($personnel_no)
            ->families;
    }

    public function show($personnel_no)
    {
        return $this->findFamilyByPersonnel($personnel_no);
    }

    public function spouse($personnel_no)
    {
        $families = $this->findFamilyByPersonnel($personnel_no);
        
        $spouse = $families->filter(function ($value, $key) {
            return $value->FAMSA == 'M1';
        });
        
        return $spouse;
    }

    public function parents($personnel_no)
    {
        $families = $this->findFamilyByPersonnel($personnel_no);
        
        $parents = $families->filter(function ($value, $key) {
            return $value->FAMSA == 'M4';
        });
        
        return $parents;
    }

    public function children($personnel_no)
    {
        $families = $this->findFamilyByPersonnel($personnel_no);
        
        $children = $families->filter(function ($value, $key) {
            return $value->FAMSA == 'M3';
        });
        
        return $children;
    }
}
