<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class StructDispController extends Controller
{
     public function show($empnik)
    {
        // menampilkan structdisp
        return \App\Models\StructDisp::where('empnik', $empnik)->get();
    }

    public function subordinates($empnik)
    {
        // mencari seluruh semua bawahan
        return Employee::where('personnel_no', $empnik)
            ->first()
            ->subordinates();
    }

    public function bosses($empnik)
    {
        // mencari seluruh atasan
        return Employee::where('personnel_no', $empnik)
            ->first()
            ->bosses();
    }

    public function closestBoss($empnik)
    {
        // mencari atasan satu tingkat di atas
        return Employee::where('personnel_no', $empnik)
            ->first()
            ->closestBoss();
    }

    public function minSuperintendentBoss($empnik)
    {
        // mencari atasan dengan minimal level CS
        // apabila tidak ditemukan maka cari di level BS
        // apabila tidak ditemukan di level BS
        // maka cari di level AS    
        $superintendent = Employee::where('personnel_no', $empnik)
            ->first()
            ->superintendentBoss();
        
        if (!$superintendent)
            return $this->minManagerBoss($empnik);
        else
            return $superintendent;
    }
    
    public function minManagerBoss($empnik)
    {
        // meneruskan recursive call dari atas
        $manager = Employee::where('personnel_no', $empnik)
            ->first()
            ->managerBoss();
        
        if (!$manager)
            return $this->minGeneralManagerBoss($empnik);
        else
            return $manager;
    }

    public function minGeneralManagerBoss($empnik)
    {
        // meneruskan recursive call dari atas
        return Employee::where('personnel_no', $empnik)
            ->first()
            ->generalManagerBoss();
    }
}
