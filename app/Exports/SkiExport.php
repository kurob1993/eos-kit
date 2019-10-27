<?php

namespace App\Exports;

use App\Models\Ski;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class SkiExport implements FromView
{
    use Exportable;

    public function forYear($year)
    {
        $this->year = $year;

        return $this;
    }

    public function forMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    public function forStage($stage)
    {
        $this->stage = $stage;

        return $this;
    }

    public function forText($text)
    {
        $this->text = $text;

        return $this;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $text = $this->text;
        $data = Ski::with('skiDetail', 'skiApproval', 'stage');
        
        if(isset($this->month)){
            $data->where('month', $this->month);
        }
        if(isset($this->year)){
            $data->where('year', $this->year);
        }
        if(isset($this->stage)){
            $data->where('stage_id', $this->stage);
        }
        if(isset($this->text)){
            $data->where(function ($query) use ($text) {
                $query->orWhere('personnel_no', 'like', '%' . $text . '%')
                    ->orWhereHas('user', function ($query) use ($text) {
                        $query->where('name', 'like', '%' . $text . '%');
                    });
            });
        }

        return view('all_ski.export', [
            'ski' => $data->get()
        ]);
    }
}
