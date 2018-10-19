<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class StructDispController extends Controller
{
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
        // mencari seluruh semua bawahan
        return Employee::findByPersonnel($empnik)
            ->first();
    }

    public function subordinates($empnik)
    {
        // mencari seluruh semua bawahan
        return Employee::findByPersonnel($empnik)
            ->first()
            ->subordinates();
    }

    public function bosses($empnik)
    {
        // mencari seluruh atasan
        return Employee::findByPersonnel($empnik)
            ->first()
            ->bosses();
    }

    public function closestBoss($empnik)
    {
        // mencari atasan satu tingkat di atas
        return Employee::findByPersonnel($empnik)
            ->first()
            ->closestBoss();
    }

    public function minSuperintendentBoss($empnik)
    {
        // mencari atasan minimal golongan CS
        return Employee::findByPersonnel($empnik)
            ->first()
            ->minSuperintendentBoss();
    }
    
    public function minManagerBoss($empnik)
    {
        // mencari atasan minimal golongan BS
        return Employee::findByPersonnel($empnik)
            ->first()
            ->minManagerBoss();
    }

    public function generalManagerBoss($empnik)
    {
        // mencari atasan minimal golongan AS
        return Employee::findByPersonnel($empnik)
            ->first()
            ->generalManagerBoss();
    }
}
