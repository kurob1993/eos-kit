<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Datatables;
use Yajra\DataTables\Html\Builder;
use App\Http\Requests\StoreAbsenceRequest;
use App\Models\Absence;
use App\Models\AbsenceQuota;

class LeaveController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
        // ambil data cuti untuk user tersebut
        $absences = Absence::ofLoggedUser()
            ->LeavesOnly()
            ->with(['absenceType', 'stage'])
            ->get();

        // response untuk datatables absences
        if ($request->ajax()) {

            return Datatables::of($absences)
                ->editColumn('summary', function ($absence) {
                    // kolom summary menggunakan view _summary
                    return view('leaves._summary', [ 
                        'summary' => $absence,
                        'when' => $absence->created_at->format('d/m') 
                    ]);
                })
                ->editColumn('approver', function ($absence) {
                    // personnel_no dan name atasan
                    return view('layouts._personnel-no-with-name', [
                        'personnel_no' => $absence->absenceApprovals->first()->user['personnel_no'],
                        'employee_name' => $absence->absenceApprovals->first()->user['name'],
                    ]);
                })
                ->setRowAttr([
                    // href untuk dipasang di setiap tr
                    'data-href' => function ($absence) {
                        return route('leaves.show', ['leaf' => $absence->id]);
                    } 
                ])
                ->escapeColumns([0,1])
                ->make(true);
        }

        // disable paging, searching, details button but enable responsive
        $htmlBuilder->parameters([
            'paging' => false,
            'searching' => false,
            'responsive' => [ 'details' => false ],
            "columnDefs" => [ [ "width" => "60%", "targets" => 0 ] ]
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
        return view('leaves.index')->with(compact('html', 'absences'));
    }

    public function create()
    {
        try {
            // mendapatkan data employee dari user
            // dan mengecek apakah dapat melakukan pelimpahan
            $canDelegate = Auth::user()->employee()->firstOrFail()->canDelegate();

        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa tidak ada data karyawan yang bisa ditemukan
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Tidak ditemukan data karyawan. Silahkan hubungi Divisi HCI&A."
            ]);
            // batalkan view create dan kembali ke parent
            return redirect()->route('leaves.index');
        }

        try {
             // mendapatkan absence quota berdasarkan user
            $absenceQuota = AbsenceQuota::activeAbsenceQuota(Auth::user()->personnel_no)
            ->with('absenceType:id,text')->firstOrFail();

        } catch(ModelNotFoundException $e) {
            // tampilkan pesan bahwa tidak ada absence quota
            Session::flash("flash_notification", [
                "level"=>"danger",
                "message"=>"Belum ada kuota cuti untuk periode saat ini. " . 
                    "Silahkan hubungi Divisi HCI&A."
            ]);
            // batalkan view create dan kembali ke parent
            return redirect()->route('leaves.index');
        }  

        // tampilkan view create
        return view('leaves.create', [
            'can_delegate' => $canDelegate,
            'absence_quota' => $absenceQuota
            ]
        );
    }

    public function store(StoreAbsenceRequest $request)
    {
        // tampilkan pesan bahwa telah berhasil mengajukan cuti
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyimpan pengajuan cuti.",
        ]);

        // membuat pengajuan cuti dengan menambahkan data personnel_no
        $absence = Absence::create($request->all()
             + ['personnel_no' => Auth::user()->personnel_no]);

        return redirect()->route('leaves.index');
    }

    public function show($id)
    {
        $leave = Absence::find($id)
            ->load(['absenceType', 'stage', 'absenceApprovals']);

        $leaveId = $leave->id;
        
        return view('leaves.show', compact('leave', 'leaveId'));
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
