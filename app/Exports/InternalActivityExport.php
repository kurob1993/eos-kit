<?php

namespace App\Exports;

use App\Models\InternalActivity;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class InternalActivityExport implements FromView
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

    public function forStage(int $stage)
    {
        $this->stage = $stage;
        
        return $this;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $activity = InternalActivity::where('id','<>',null);
        if ($this->month !== 0) {
            $activity->whereMonth('start_date',$this->month);
        }
        if ($this->year !== 0) {
            $activity->whereYear('start_date',$this->year);
        }
        if ($this->stage !== 0) {
            $activity->where('stage_id', $this->stage);
        }
        return view('all_internal_activity.export', [
            'activity' => $activity->get()
        ]);
    }
}
