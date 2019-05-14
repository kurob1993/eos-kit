<?php

namespace App\Http\Controllers\Dashboard\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\AbsenceQuota;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    protected $user, $subordinates, $chartThemes;
    protected $lfmonths, $lfyears, $lfboss, $lfsubordinatesboss, $lfsubordinates;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // user yang sedang login
            $this->user = Auth::user()->employee;

            // bawahan dari user yang sedang login
            $this->subordinates = $this->user->subordinates()
                ->pluck('personnel_no', 'name');

            // konfigurasi fusionchart default
            $this->chartThemes = [
                "theme" => "fusion",
                "baseFont" => "Karla",
                "baseFontColor" => "#153957",
                "outCnvBaseFont" => "Karla",
            ];

            return $next($request);
        });
    }

    public function monthNumToText($num)
    {
        return date("F", mktime(0, 0, 0, $num, 1));
    }

    protected function leaveSubBoss(Request $request)
    {
        // bawahan yang memiliki bawahan? haha
        // option value untuk select bawahan dari orang yang sedang login
        $this->lfsubordinatesboss = $this->user->closestStructuralSubordinates();
        // masukkan diri sendiri pada elemen terkahir
        $this->lfsubordinatesboss->push($this->user);

        // value untuk select filter & parameter query
        // cari bawahan dari atasanya yang telah dipilih
        if ($request->has('lfboss')) {
            $this->lfboss = Employee::find($request->lfboss);
        } else {
            $this->lfboss = $this->lfsubordinatesboss->first();
        }

        // cari bawahan dari lfboss
        $this->lfsubordinates = $this->lfboss->subordinates();
    }

    public function leaveFilter(Request $request)
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
        $this->lfmonths = $months->sortByDesc('number')->values();

        // mencari tahun-tahun yang valid untuk select filter
        $this->lfyears = Absence::selectRaw('year(start_date) as year')
            ->whereIn('personnel_no', $this->subordinates)
            ->leavesOnly()
            ->successOnly()
            ->groupBy('year')
            ->orderByRaw('year DESC')
            ->get();

        // inisialisasi bawahan dari atasan terpilih
        $this->leaveSubBoss($request);

        return response()
            ->json([
                'lfmonths' => $this->lfmonths,
                'lfyears' => $this->lfyears,
                'lfsubordinatesboss' => $this->lfsubordinatesboss,
            ]);
    }

    public function leaveChart(Request $request)
    {
        // bulan dan tahun yang telah dipilih
        $lfmonth = $request->has('lfmonth') ?
            $request->lfmonth : (!is_null($this->lfmonths) ?
                sprintf('%02d', $this->lfmonths->first()->month) : date('m'));
        $lfyear = $request->has('lfyear') ?
            $request->lfyear : (!is_null($this->lfyears) ?
                $this->lfyears->first()->year : date('Y'));

        // inisialisasi bawahan dari atasan terpilih
        $this->leaveSubBoss($request);

        // mencari data lembur untuk bawahan sesuai parameter di atas
        $absenceQuotas = AbsenceQuota::select(['personnel_no', 'number', 'deduction'])
            ->whereIn('personnel_no', $this->lfsubordinates->pluck('personnel_no'))
            ->quotaOf(
                date($lfyear . '-' . $lfmonth . '-01'),
                date($lfyear . '-' . $lfmonth . '-t')
            )
            ->get();

        // full outer join emulation
        $unsorted = $this->lfsubordinates->map(function ($item) use ($absenceQuotas) {
            $search = $absenceQuotas->where('personnel_no', $item->personnel_no);
            $number = $search->isNotEmpty() ? $search->first()->number : null;
            $deduction = $search->isNotEmpty() ? $search->first()->deduction : null;

            return [
                'personnel_no' => $item->personnel_no,
                'name' => $item->name,
                'number' => $number,
                'deduction' => $deduction,
            ];
        });

        // sorting dan rebuild new keys
        $leaveChartData = $unsorted->sortByDesc('deduction')->values();

        // reformat struktur array
        $leaveChartCat = $leaveChartData->map(function ($item, $key) {
            return ['label' => $item['personnel_no'] . ' - ' . $item['name']];
        });
        $leaveChartQuota = $leaveChartData->map(function ($item, $key) {
            return ['value' => $item['number']];
        });
        $leaveChartDeduction = $leaveChartData->map(function ($item, $key) {
            return ['value' => $item['deduction']];
        });

        // JSON yang di-return
        $chartOptions = [
            'caption' => 'Cuti Karyawan ' . $this->lfboss->org_unit_name,
            'subcaption' => $this->monthNumToText($lfmonth) . ' ' . $lfyear,
            'xaxisname' => 'Karyawan',
            'yaxisname' => 'Durasi cuti (hari)',
        ];
        $dataSource = [
            'chart' => array_merge($chartOptions, $this->chartThemes),
            'categories' => [['category' => $leaveChartCat]],
            'dataset' => [
                ['seriesname' => 'Kuota', 'data' => $leaveChartQuota],
                ['seriesname' => 'Terpakai', 'data' => $leaveChartDeduction],
            ],
        ];

        // kembalikan JSON
        return response()->json($dataSource);
    }
}
