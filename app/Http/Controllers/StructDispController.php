<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\StructDisp;

class StructDispController extends Controller
{
    protected function findByPersonnel($empnik)
    {
        // menampilkan informasi karyawan
        return Employee::findByPersonnel($empnik)
            ->first();
    }

    public function index()
    {
        $paginated = \App\Models\StructDisp::selfStruct()->paginate();
        $paginated->transform(function ($item, $key) {
            return Employee::findByPersonnel($item->empnik)->first();
        });

        return $paginated;
    }

    public function show($empnik)
    {
        // menampilkan informasi karyawan
        $employee = $this->findByPersonnel($empnik);

        if (!is_null($employee))
            return $employee;
        else
            return []; 
    
    }

    public function showByCostCenter($cost_center)
    {
        return Employee::findByCostCenter($cost_center)
            ->get();
    }

    public function showByShortAbbrOrg($abbr_org)
    {
        return StructDisp::findByShortAbbrOrg($abbr_org)
            ->get();
    }

    public function subordinates($empnik)
    {
        // mencari seluruh semua bawahan
        $employee = $this->findByPersonnel($empnik);

        if (!is_null($employee))
            return $employee->subordinates();
        else
            return [];
    }

    public function foremanAndOperatorSubordinates($empnik)
    {
        // mencari seluruh semua bawahan
        $employee = $this->findByPersonnel($empnik);

        if (!is_null($employee))
            return $employee->foremanAndOperatorSubordinates();
        else
            return [];
    }

    public function bosses($empnik)
    {
        // mencari seluruh atasan
        $employee = $this->findByPersonnel($empnik);

        if (!is_null($employee))
            return $employee->bosses();
        else
            return [];
    }

    public function closestBoss($empnik)
    {
        // mencari atasan satu tingkat di atas
        $employee = $this->findByPersonnel($empnik);

        if (!is_null($employee))
            return $employee->closestBoss();
        else
            return [];
    }

    public function minSuperintendentBoss($empnik)
    {
        // mencari atasan minimal golongan CS
        $employee = $this->findByPersonnel($empnik);
        
        if (!is_null($employee))
            return $employee->minSuperintendentBoss();
        else 
            return [];
    }
    
    public function minManagerBoss($empnik)
    {
        // mencari atasan minimal golongan BS
        $employee = $this->findByPersonnel($empnik);
        
        if (!is_null($employee))
            return $employee->minManagerBoss();
        else 
            return [];
    }

    public function generalManagerBoss($empnik)
    {
        // mencari atasan minimal golongan AS
        $employee = $this->findByPersonnel($empnik);
        
        if (!is_null($employee))
            return $employee->generalManagerBoss();
        else 
            return [];
    }

    public function minSuperintendentWithDelegation($empnik)
    {
        // mencari atasan minimal golongan CS
        $employee = $this->findByPersonnel($empnik);
        
        if (!is_null($employee))
            return $employee->minSuperintendentWithDelegation();
        else 
            return [];
    }
    
    public function minManagerBossWithDelegation($empnik)
    {
        // mencari atasan minimal golongan BS
        $employee = $this->findByPersonnel($empnik);
        
        if (!is_null($employee))
            return $employee->minManagerWithDelegation();
        else 
            return [];
    }

    public function superintendentWithDelegation($empnik)
    {
        // mencari atasan minimal golongan CS
        $employee = $this->findByPersonnel($empnik);
        
        if (!is_null($employee))
            return $employee->superintendentWithDelegation();
        else 
            return [];
    }
    
    public function managerWithDelegation($empnik)
    {
        // mencari atasan minimal golongan BS
        $employee = $this->findByPersonnel($empnik);
        
        if (!is_null($employee))
            return $employee->managerWithDelegation();
        else 
            return [];
    }
}
