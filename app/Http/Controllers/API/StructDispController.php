<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\SAP\StructDisp;

class StructDispController extends Controller
{
    protected function findByPersonnel($empnik)
    {
        // menampilkan informasi karyawan
        return Employee::findByPersonnel($empnik)
            ->first();
    }

    public function index(Request $request)
    {
        $per_page = isset($request->per_page) ? $request->per_page : 15;

        $disp = StructDisp::selfStruct()->paginate($per_page);
        $disp->transform(function ($item, $key) {
            $emp = StructDisp::select(
                'empnik as personnel_no',
                'empname as name',
                'emppersk as esgrp',
                'empkostl as cost_ctr',
                'emppostx as position_name',
                'emportx as org_unit_name',
                'emp_hrp1000_o_short as kode_unit',
                'emporid'
                )->where('empnik',$item->empnik)->first();
            return array_add($emp->toArray(),'divisi',$emp->minDivisi());
        });
        return $disp;
    }

    public function show($empnik)
    {
        // menampilkan informasi karyawan
        $employee = $this->findByPersonnel($empnik);
        $disp = StructDisp::where('empnik',$empnik)
            ->selfStruct()
            ->get();
        
        $disp->transform(function ($item, $key) use ($employee) {
            return array_add($employee->toArray(),'divisi',$item->minDivisi());
        });
        
        if (count($disp) !== 0){
            return $disp[0];
        }

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

    public function minSptBossWithDelegation($empnik)
    {
        // mencari atasan minimal golongan CS
        $employee = $this->findByPersonnel($empnik);
        
        if (!is_null($employee))
            return $employee->minSptBossWithDelegation();
        else 
            return [];
    }
    
    public function minManagerBossWithDelegation($empnik)
    {
        // mencari atasan minimal golongan BS
        $employee = $this->findByPersonnel($empnik);
        
        if (!is_null($employee))
            return $employee->minManagerBossWithDelegation();
        else 
            return [];
    }

    public function sptBossWithDelegation($empnik)
    {
        // mencari atasan minimal golongan CS
        $employee = $this->findByPersonnel($empnik);
        
        if (!is_null($employee))
            return $employee->sptBossWithDelegation();
        else 
            return [];
    }
    
    public function managerBossWithDelegation($empnik)
    {
        // mencari atasan minimal golongan BS
        $employee = $this->findByPersonnel($empnik);
        
        if (!is_null($employee))
            return $employee->managerBossWithDelegation();
        else 
            return [];
    }

    public function gmBossWithDelegation($empnik)
    {
        // mencari atasan minimal golongan BS
        $employee = $this->findByPersonnel($empnik);
        
        if (!is_null($employee))
            return $employee->gmBossWithDelegation();
        else 
            return [];
    }
    
    public function clossestSubordinates($empnik)
    {
        // mencari atasan satu tingkat di atas
        $employee = $this->findByPersonnel($empnik);

        if (!is_null($employee))
            return $employee->closestSubordinates();
        else
            return [];
    }
}
