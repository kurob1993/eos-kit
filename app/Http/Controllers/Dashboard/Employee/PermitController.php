<?php

namespace App\Http\Controllers\Dashboard\Employee;

use Illuminate\Http\Request;
use App\Models\Absence;
use App\Models\Attendance;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Models\Employee;

class PermitController extends DashboardController
{
    protected $pfmonths, $pfyears, $pfboss, $pfsubordinatesboss, $pfsubordinates;

    protected function subBoss(Request $request)
    {
        // bawahan yang memiliki bawahan? haha
        // option value untuk select bawahan dari orang yang sedang login
        $this->pfsubordinatesboss = $this->user->closestStructuralSubordinates();
        // masukkan diri sendiri pada elemen terkahir
        $this->pfsubordinatesboss->push($this->user);

        // value untuk select filter & parameter query
        // cari bawahan dari atasanya yang telah dipilih
        if ($request->has('pfboss')) {
            $this->pfboss = Employee::find($request->pfboss);
        } else {
            $this->pfboss = $this->pfsubordinatesboss->first();
        }

        // cari bawahan dari pfboss
        $this->pfsubordinates = $this->pfboss->subordinates();
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
        $this->pfmonths = $months->sortByDesc('number')->values();

        // mencari tahun-tahun yang valid untuk select filter
        $this->pfyears = Absence::selectRaw('year(start_date) as year')
            ->whereIn('personnel_no', $this->subordinates)
            ->excludeLeaves()
            ->successOnly()
            ->groupBy('year')
            ->orderByRaw('year DESC')
            ->get();

        // inisialisasi bawahan dari atasan terpilih
        $this->subBoss($request);

        return response()
            ->json([
                'pfmonths' => $this->pfmonths,
                'pfyears' => $this->pfyears,
                'pfsubordinatesboss' => $this->pfsubordinatesboss,
            ]);
    }

    public function chart(Request $request)
    {
        // bulan dan tahun yang telah dipilih
        $pfmonth = $request->has('pfmonth') ?
            $request->pfmonth : (!is_null($this->pfmonths) ?
                sprintf('%02d', $this->pfmonths->first()->month) : date('m'));
        $pfyear = $request->has('pfyear') ?
            $request->pfyear : (!is_null($this->pfyears) ?
                $this->pfyears->first()->year : date('Y'));

        // inisialisasi bawahan dari atasan terpilih
        $this->subBoss($request);

        // mencari data lembur untuk bawahan dari atasan yang telah dipilih
        $overtimeChartData = Absence::selectRaw(
            'CONCAT(absences.personnel_no, " - ", employees.name) as label, ' .
                'SUM(TIMESTAMPDIFF(SECOND, start_date, end_date)/3600) AS value'
        )
            ->whereIn('absences.personnel_no', $this->pfsubordinates->pluck('personnel_no'))
            ->leftJoin('employees', 'absences.personnel_no', '=', 'employees.personnel_no')
            ->monthYearOf($pfmonth, $pfyear)
            ->successOnly()
            ->groupBy('absences.personnel_no')
            ->orderByRaw('value DESC')
            ->get();

        $chartOptions = [
            "caption" => "Izin Karyawan " . $this->pfboss->org_unit_name,
            "subcaption" => $this->monthNumToText($pfmonth) . ' ' . $pfyear,
            "xaxisname" => "Karyawan",
            "yaxisname" => "Total izin (jam)",
        ];
        $dataSource = [
            "chart" => array_merge($chartOptions, $this->chartThemes),
            "data" => $overtimeChartData
        ];

        // kembalikan JSON
        return response()->json($dataSource);
    }
}
