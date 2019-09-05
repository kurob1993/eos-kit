<?php

namespace App\Exports;

use App\Models\Preferdis;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class PreferdisExport implements FromView
{
    use Exportable;

    public function forPeriode(int $periode)
    {
        $this->periode = $periode;
        
        return $this;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $data = Preferdis::where('preferdis_periode_id',$this->periode)
                    ->get([
                        'preferdis_periode_id',
                        'sobid',
                        'stext',
                        'begda'
                        ]
                    );

        $dataGroup = $data->groupBy('sobid');

        return view('preferences._export', [
            'preferences' => $dataGroup
        ]);
    }
}
