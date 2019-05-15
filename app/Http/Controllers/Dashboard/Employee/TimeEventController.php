<?php

namespace App\Http\Controllers\Dashboard\Employee;

use Illuminate\Http\Request;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Models\Employee;
use App\Models\TimeEvent;

class TimeEventController extends DashboardController
{
    protected $temonths, $teyears, $teboss, $tesubordinatesboss, $tesubordinates;

    protected function subBoss(Request $request)
    {
        // bawahan yang memiliki bawahan? haha
        // option value untuk select bawahan dari orang yang sedang login
        $this->tesubordinatesboss = $this->user->closestStructuralSubordinates();
        // masukkan diri sendiri pada elemen terkahir
        $this->tesubordinatesboss->push($this->user);

        // value untuk select filter & parameter query
        // cari bawahan dari atasanya yang telah dipilih
        if ($request->has('teboss')) {
            $this->teboss = Employee::find($request->teboss);
        } else {
            $this->teboss = $this->tesubordinatesboss->first();
        }

        // cari bawahan dari teboss
        $this->tesubordinates = $this->teboss->subordinates();
    }

    public function filter(Request $request)
    {
        // mencari bulan-bulan yang valid untuk select filter
        $months = collect();
        foreach (range(1, 12) as $m) {
            if ($m == intval(date('m'))) break;
            $months->push([
                'number' => $m,
                'name' => $this->monthNumToText($m),
            ]);
        }
        $this->temonths = $months->sortByDesc('number')->values();

        // mencari tahun-tahun yang valid untuk select filter
        $this->teyears = TimeEvent::selectRaw('year(check_date) as year')
            ->whereIn('personnel_no', $this->subordinates)
            ->successOnly()
            ->groupBy('year')
            ->orderByRaw('year DESC')
            ->get();

        // inisialisasi bawahan dari atasan terpilih
        $this->subBoss($request);

        return response()
            ->json([
                'temonths' => $this->temonths,
                'teyears' => $this->teyears,
                'tesubordinatesboss' => $this->tesubordinatesboss,
            ]);
    }

    public function chart(Request $request)
    {
        // bulan dan tahun yang telah dipilih
        $temonth = $request->has('temonth') ?
            $request->temonth : (!is_null($this->temonths) ?
                sprintf('%02d', $this->temonths->first()->month) : date('m'));
        $teyear = $request->has('teyear') ?
            $request->teyear : (!is_null($this->teyears) ?
                $this->teyears->first()->year : date('Y'));

        // inisialisasi bawahan dari atasan terpilih
        $this->subBoss($request);

        // mencari data lembur untuk bawahan dari atasan yang telah dipilih
        $timeEventChartData = TimeEvent::selectRaw(
            'CONCAT(time_events.personnel_no, " - ", employees.name) as label, ' .
                'COUNT(check_date) AS value'
        )
            ->whereIn('time_events.personnel_no', $this->tesubordinates->pluck('personnel_no'))
            ->leftJoin('employees', 'time_events.personnel_no', '=', 'employees.personnel_no')
            ->monthYearOf($temonth, $teyear)
            ->successOnly()
            ->groupBy('time_events.personnel_no')
            ->orderByRaw('value DESC')
            ->get();

        $chartOptions = [
            "caption" => "Tidak Slash Karyawan " . $this->teboss->org_unit_name,
            "subcaption" => $this->monthNumToText($temonth) . ' ' . $teyear,
            "xaxisname" => "Karyawan",
            "yaxisname" => "Total Tidak Slash",
        ];
        $dataSource = [
            "chart" => array_merge($chartOptions, $this->chartThemes),
            "data" => $timeEventChartData
        ];

        // kembalikan JSON
        return response()->json($dataSource);
    }
}
