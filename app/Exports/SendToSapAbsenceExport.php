<?php

namespace App\Exports;

use App\Models\Absence;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class SendToSapAbsenceExport implements FromView
{
    use Exportable;

    public function forYear(int $year)
    {
        $this->year = $year;
        
        return $this;
    }

    public function forMonth(int $month)
    {
        $this->month = $month;
        
        return $this;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('sendtosap.export', [
            'absence' => Absence::whereMonth('start_date',$this->month)
                            ->whereYear('start_date',$this->year)
                            ->where('stage_id','2')
                            ->where('sendtosap_at','<>',null)
                            ->get(),
            'relasi' => 'absenceSapResponse'
        ]);
    }
}
