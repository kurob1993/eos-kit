<?php

namespace App\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Models\Ski;
use App\Models\SkiDetail;
use App\Models\OvertimeReason;

class SkiController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        $allowed = Auth::user()->employee->allowedToSubmitSubordinateOvertime();

        if ($allowed) {
            $lembur = "Daftar Sasaran Kerja";
            $subordinates = Auth::user()->employee->subordinates();

            $personal_no = [];
            foreach ($subordinates as $subordinate) {
                array_push($personal_no,$subordinate->personnel_no);
            }
            array_push($personal_no, Auth::user()->personnel_no);
            $overtimes = Ski::whereIn('personnel_no',$personal_no)->get();
        } else {
            // ambil data cuti untuk user tersebut
            $lembur = "Daftar Sasaran Kerja Saya";
            $personal_no = Auth::user()->personnel_no;
            $overtimes = Ski::where('personnel_no',$personal_no)->get();
        }

        // response untuk datatables attendanceQuota
        if ($request->ajax()) {

            return Datatables::of($overtimes)
                ->editColumn('summary', function ($overtime) {
                    // kolom summary menggunakan view _summary
                    return view('ski._summary', [
                        'summary' => $overtime,
                        'when' => $overtime->created_at->format('d/m')
                    ]);
                })
                ->editColumn('approver', function ($overtime) {
                    // personnel_no dan name atasan
                    $views = '';
                    foreach ($overtime->skiApproval as $item) {
                        $views =  $views . view('layouts._personnel-no-with-name', [
                            'personnel_no' => $item->employee['personnel_no'],
                            'employee_name' => $item->employee['name'],
                        ]) . '<br />';
                    }
                    return $views;
                })
                ->setRowAttr([
                    // href untuk dipasang di setiap tr
                    'data-href' => function ($ski) {
                        return route('ski.show', ['ski' => $ski->id]);
                    }
                ])
                ->escapeColumns([0, 1])
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'paging' => false,
            'searching' => false,
            'responsive' => ['details' => false],
            "columnDefs" => [["width" => "60%", "targets" => 0]]
        ]);

        $html = $htmlBuilder
            ->addColumn([
                'data' => 'summary',
                'name' => 'summary',
                'title' => 'Summary',
                'searchable' => false,
                'orderable' => false,
            ])
            ->addColumn([
                'data' => 'approver',
                'name' => 'approver',
                'title' => 'Approver',
                'class' => 'desktop',
                'searchable' => false,
                'orderable' => false,
            ]);

        // tampilkan view index dengan tambahan script html DataTables
        return view('ski.index')->with(compact('html', 'overtimes', 'lembur'));
    }

    public function create()
    {
        // user yang dapat melakukan pengajuan lembur
        $user = Auth::user()->personnel_no;

        // alasan lembur
        $overtime_reason = OvertimeReason::all('id', 'text')
            ->mapWithKeys(function ($item) {
                return [$item['id'] => $item['text']];
            })
            ->all();

        // mengecek apakah boleh mengajukan overtime untuk bawahan
        $allowed = Auth::user()
            ->employee
            ->allowedToSubmitSubordinateOvertime();

        if ($allowed) {
            // route untuk menyimpan from employee
            $formRoute = route('ski.store');
            $pageContainer = 'layouts.employee._page-container';

            // menampilkan view create overtime secretary
            return view(
                'ski.createas',
                compact('overtime_reason', 'user', 'formRoute', 'pageContainer')
            );
        } else {

            Session::flash("flash_notification", [
                "level" => "danger",
                "message" => "Mohon maaf, Anda tidak dapat input Sasaran Kerja. " .
                    "Silahkan hubungi Supervisor/Superintendent/Sekretaris Divisi " .
                    "untuk mengajukan Sasaran Kerja Anda."
            ]);
            // batalkan view create dan kembali ke parent
            return redirect()->route('ski.index');
        }
    }

    public function store(Request $request)
    {
        $ski = new Ski();
        $ski->personnel_no = $request->personnel_no;
        $ski->month = $request->bulan;
        $ski->year = $request->tahun;
        $ski->perilaku = $request->perilkau;
        $ski->stage_id = 1;
        $ski->dirnik = Auth::user()->personnel_no;

        if ($ski->save()) {
            foreach ($request->klp as $key => $value) {
                if ($value !== null) {
                    $skid = new SkiDetail();
                    $skid->ski_id = $ski->id;
                    $skid->klp = $value;
                    $skid->sasaran = $request->sasaran[$key];
                    $skid->kode = $request->kode[$key];
                    $skid->ukuran = $request->ukuran[$key];
                    $skid->bobot = $request->bobot[$key];
                    $skid->skor = $request->skor[$key];
                    $skid->nilai = $request->nilai[$key];
                    $skid->save();
                }
            }
        }

        // // kembali ke halaman index overtime
        return redirect()->route('ski.index');
    }

    public function show($id)
    {
        $ski = Ski::find($id)
            ->load(['stage', 'skiApproval','skiDetail']);

        $skiId = $ski->id;

        return view('ski.show', compact('ski', 'skiId'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
