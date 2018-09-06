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
        // mencari atasan minimal golongan CS
        return Employee::where('personnel_no', $empnik)
            ->first()
            ->minSuperintendentBoss();
    }
    
    public function minManagerBoss($empnik)
    {
        // mencari atasan minimal golongan BS
        return Employee::where('personnel_no', $empnik)
            ->first()
            ->minManagerBoss();
    }

    public function generalManagerBoss($empnik)
    {
        // mencari atasan minimal golongan AS
        return Employee::where('personnel_no', $empnik)
            ->first()
            ->generalManagerBoss();
    }
}
