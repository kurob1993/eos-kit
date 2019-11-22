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

    public function forDivisi($divisi)
    {
        $this->divisi = $divisi;

        return $this;
    }
    
    public function forType($type)
    {
        $this->type = $type;

        return $this;
    }
    
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $text = $this->text;
        $nik = explode(',', $text);
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
            $data->whereIn('personnel_no',$nik);
        }
        if(isset($this->divisi)){
            $data->where('divisi',$this->divisi);
        }
    

        if($this->type == 'all'){
           $view = 'all_ski.export';
        }

        if($this->type == 'rekap'){
            $view = 'all_ski.export_rekap';
        }
        return view($view, [ 'ski' => $data->get() ]);
        
    }
}
