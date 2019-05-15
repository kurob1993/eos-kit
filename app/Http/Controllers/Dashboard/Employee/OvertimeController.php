<?php

namespace App\Http\Controllers\Dashboard\Employee;

use Illuminate\Http\Request;
use App\Models\AttendanceQuota as Overtime;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Models\Employee;

class OvertimeController extends DashboardController
{
    protected $ofmonths, $ofyears, $ofboss, $ofsubordinatesboss, $ofsubordinates;

    protected function subBoss(Request $request)
    {
        // bawahan yang memiliki bawahan? haha
        // option value untuk select bawahan dari orang yang sedang login
        $this->ofsubordinatesboss = $this->user->closestStructuralSubordinates();
        // masukkan diri sendiri pada elemen terkahir
        $this->ofsubordinatesboss->push($this->user);

        // value untuk select filter & parameter query
        // cari bawahan dari atasanya yang telah dipilih
        if ($request->has('ofboss')) {
            $this->ofboss = Employee::find($request->ofboss);
        } else {
            $this->ofboss = $this->ofsubordinatesboss->first();
        }

        // cari bawahan dari ofboss
        $this->ofsubordinates = $this->ofboss->subordinates();
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
        $this->ofmonths = $months->sortByDesc('number')->values();

        // mencari tahun-tahun yang valid untuk select filter
        $this->ofyears = Overtime::selectRaw('year(start_date) as year')
            ->whereIn('personnel_no', $this->subordinates)
            ->successOnly()
            ->groupBy('year')
            ->orderByRaw('year DESC')
            ->get();

        // inisialisasi bawahan dari atasan terpilih
        $this->subBoss($request);

        return response()
            ->json([
                'ofmonths' => $this->ofmonths,
                'ofyears' => $this->ofyears,
                'ofsubordinatesboss' => $this->ofsubordinatesboss,
            ]);
    }

    public function chart(Request $request)
    {
        // bulan dan tahun yang telah dipilih
        $ofmonth = $request->has('ofmonth') ?
            $request->ofmonth : (!is_null($this->ofmonths) ?
                sprintf('%02d', $this->ofmonths->first()->month) : date('m'));
        $ofyear = $request->has('ofyear') ?
            $request->ofyear : (!is_null($this->ofyears) ?
                $this->ofyears->first()->year : date('Y'));

        // inisialisasi bawahan dari atasan terpilih
        $this->subBoss($request);

        // mencari data lembur untuk bawahan dari atasan yang telah dipilih
        $overtimeChartData = Overtime::selectRaw(
            'CONCAT(attendance_quotas.personnel_no, " - ", employees.name) as label, ' .
                'SUM(TIMESTAMPDIFF(SECOND, start_date, end_date)/3600) AS value'
        )
            ->whereIn('attendance_quotas.personnel_no', $this->ofsubordinates->pluck('personnel_no'))
            ->leftJoin('employees', 'attendance_quotas.personnel_no', '=', 'employees.personnel_no')
            ->monthYearOf($ofmonth, $ofyear)
            ->successOnly()
            ->groupBy('attendance_quotas.personnel_no')
            ->orderByRaw('value DESC')
            ->get();

        $chartOptions = [
            "caption" => "Lembur Karyawan " . $this->ofboss->org_unit_name,
            "subcaption" => $this->monthNumToText($ofmonth) . ' ' . $ofyear,
            "xaxisname" => "Karyawan",
            "yaxisname" => "Total lembur (jam)",
        ];
        $dataSource = [
            "chart" => array_merge($chartOptions, $this->chartThemes),
            "data" => $overtimeChartData
        ];

        // kembalikan JSON
        return response()->json($dataSource);
    }
}
