<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\StoreAbsenceRequest;
use Illuminate\Support\Facades\Crypt;
use App\Models\AbsenceQuota;

class DebugController extends Controller
{
    public function debug()
    {   
        $s = new \DateTime('2018-01-06 17:00:00');
        $e = new \DateTime('2018-01-07 17:00:00');

        $a = new \DateTime('2018-01-06 17:30:00'); // beririsan
        $b = new \DateTime('2018-01-06 19:00:00'); // beririsan
        
        $x = new \DateTime('2018-01-05 05:00:00'); // tdk
        $y = new \DateTime('2018-01-06 18:00:00');

        $o = new \DateTime('2018-01-06 21:00:00'); // tdk
        $p = new \DateTime('2018-01-07 20:00:00');

        $u = new \DateTime('2018-01-06 00:00:00');
        $v = new \DateTime('2018-01-07 19:00:00');

        $sa = new \DateTime('2018-01-05 00:00:00');
        $si = new \DateTime('2018-01-06 15:00:00');

        $pa = new \DateTime('2018-01-07 20:00:00');
        $pi = new \DateTime('2018-01-08 06:00:00');

        $ki = $pa;
        $ka = $pi;

        if ( (($s>=$ki && $e<=$ka) || ($s<=$ki && $e>=$ki) || ($s<=$ka && $e>=$ka) ) )
            echo "beririsan";
        else
            echo "tidak beririsan";

        // $subordinates = Auth::user()->employee->mgrSptSpvSubordinates();
        // $leaveChartDeduction = $leaveChartQuota = $leaveChartCat = [];
        // foreach ($subordinates as $subordinate) {
        //     array_push(
        //         $leaveChartCat, 
        //         array("label" => $subordinate->personnelNoWithName)
        //     );
        //     $absence_quota = $subordinate->active_absence_quota;
        //     $number = $deduction = (is_null($absence_quota)) ? 0 : $absence_quota;
            
        //     array_push(
        //         $leaveChartQuota,
        //         array("value" => $number)
        //     );
        //     array_push(
        //         $leaveChartDeduction,
        //         array("value" => $deduction)
        //     );
        // }
        // $dataSource = [ 
        //     "categories" => [
        //         "category" => $leaveChartCat
        //     ],
        //     "dataset" => [
        //         [ "seriesname" => "Kuota", "data" => $leaveChartQuota ],
        //         [ "seriesname" => "Terpakai", "data" => $leaveChartDeduction ]
        //     ]
        // ];
        // echo json_encode($dataSource);

        // dd(Auth::guard('secr')->user()->toArray());
        // $encrypted = Crypt::encryptString('11725');
        // dd($encrypted);

        // dd(\Storage::disk('public')->exists('11725.jpg'));

        // // Jumlah item notifikasi untuk overtime
        // $countOvertimeApprovals = \App\Models\AttendanceQuotaApproval::where('regno', Auth::user()->personnel_no)
        //     ->waitedForApproval()->get()->toArray();

        // dd($countOvertimeApprovals);

        // dd(\App\Models\Employee::where('personnel_no', 11725)->first()->isSuperintendent());
        
        // dd(\Carbon\Carbon::parse('2018-09-01')->addDays(1));

        // $flow_id = \Config::get('emss.flows.absences');
        // // mencari flow berdasarkan flow_id
        // $flow = \App\Models\Flow::find($flow_id);
        // // var_dump($flow->toArray());
        // // mengakses stages melalui many to many relationship melalui pivot table
        // foreach ($flow->stages as $stage) {
        //   var_dump($stage->pivot->toArray());
        // }

        // // mencari first sequence terhadap flow_id
        // $flow_id = \Config::get('emss.flows.absences');
        // $flow = \App\Models\FlowStage::firstSequence($flow_id);
        // dd($flow->get()->toArray());
 
        // $flow = \App\Models\FlowStage::nextSequence($flow_id);
        // dd($flow->get()->toArray());

        // // mengecek kuota absence terhadap suatu rentang tanggal
        // $absence_type_id = \App\Models\AbsenceQuota::activeAbsenceQuotaOf(
        //   11725, '2018-07-13', '2018-07-13')
        //   ->first()->absenceType()->first()->toArray();
        // dd($absence_type_id);

        // melakukan querying relationship existence
        // SELECT *.a, *.b, *.c  
        // FROM absences a, absence_types b, stages c
        // WHERE a.personnel_no = $logged_user
        // AND a.absence_type_id = b.id
        // AND b.subtype = '0100'
        // $absences = Absence::where('personnel_no', Auth::user()->personnel_no)
        //     ->LeavesOnly()->with(['absenceType', 'stage'])->get();

        // $absences = Absence::where('personnel_no', Auth::user()->personnel_no)
        //     ->excludeLeaves()->with(['absenceType', 'stage'])->get();
            
        // dd($absences->toArray());

        // // apakah absence merupakan cuti tahunan / cuti besar
        // $absence = Absence::find(43);
        // dd($absence->isALeave);
        
        // // FormatDates Trait
        // dd($absence->updated_at);

        // $absences = Auth::user()->absences()->with(['absenceType', 'stage']);
        // dd($absences->get()->toArray());
        // $absences = Absence::where('personnel_no', Auth::user()->personnel_no)->with(['absenceType', 'stage'])->get();
        // $absences = Absence::where('personnel_no', Auth::user()->personnel_no)->with('absenceType:id,text')->get();
        // dd($absences->toArray());

        // // mengecek kuota absence pada hari ini
        // try { var_dump(Auth::user()->activeAbsenceQuotaType()); } 
        // catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) { var_dump($e->getMessage()); }
        
        // try {
        //     var_dump( 
        //         \App\Models\AbsenceQuota::activeAbsenceQuota(11725)->with('absenceType:id,text')->firstOrFail()->toArray()
        //     ); 
        // }
        // catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) { var_dump($e->getMessage()); }

        // mencari direktur
        // var_dump(Auth::user()->employee()->first()->director()->toArray());
        // var_dump(\App\Models\Employee::where('personnel_no', 10233)
        //     ->first()
        //     ->director()
        //     ->toArray());
        // var_dump(\App\Models\Employee::where('personnel_no', 10291)->first());

        // // mencari atasan dari personnel_no
        // $bosses = \App\Models\StructDisp::closestBossOf('10112');
        // dd($bosses->first()->toArray());

        //    // mencari atasan superintendent
        //     $bosses = \App\Models\StructDisp::SuperintendentOf('11777');
        //     dd($bosses->get()->toArray());

        // // mencari atasan dari GM
        // $bosses = \App\Models\StructDisp::closestBossOf('10233');
        // dd($bosses->first());
        // var_dump(\App\Models\Employee::where('personnel_no', 10233)
        //     ->first()
        //     ->closestBoss());        

        // // mencari atasan-atasan dari personnel_no
        // $bosses = \App\Models\StructDisp::BossesOf('11725');
        // var_dump($bosses->toArray());

        // // mencari karyawan berdasarkan golongan
        // $subgroup_struct = \App\Models\StructDisp::subgroupStructOf('ES');
        // var_dump($subgroup_struct->toArray());
    
        // // mencari data karyawan untuk user yang sudah login
        // var_dump(Auth::user()->employee()->first()->toArray());

        // // mencari semua absences dengan global where('personnel_no', Auth::user()->personnel_no)ope
        // var_dump(\App\Models\Absence::get()->toAwhere('personnel_no', Auth::user()->personnel_no)ay());

        // // mencari atasan satu tingkat di atas
        // $e = Auth::user()->employee()->first();
        // dd($e->closestBoss()->toArray());
        
        // // // mencari atasan-atasan
        // $e = Auth::user()->employee()->first();
        // var_dump($e->bosses()->toArray()); 
 
        // // // mencari bawahan-bawahan
        // $e = Auth::user()->employee()->first();
        // var_dump($e->subordinates()->toArray()); 

        // // apakah boleh melakukan pelimpahan wewenang?
        // var_dump(Auth::user()->employee()->first()->canDelegate());

        //Melihat return firstStatus pada model status
        // $status = \App\Models\Status::firstStatus()->id;
        // var_dump($status);

        // // mencoba reject dan approve status
        // $status = \App\Models\Status::approveStatus()->id;
        // var_dump($status);
        // $status = \App\Models\Status::rejectStatus()->id;
        // var_dump($status);

        // // nested relationship eager loading
        // $absenceApprovals = \App\Models\AbsenceApproval::where('regno', Auth::user()->personnel_no)
        // ->with(['status:id,description', 'absence.user.employee']);
        
        // var_dump($absenceApprovals->first()->toArray());

        // mencari  absence approval dari user yang belum disetujui
        // $needApprovals = \App\Models\AbsenceApproval::where('regno', Auth::user()->personnel_no)
        //     ->waitedForApproval()->get();
        // var_dump($needApprovals->toArray());

        // // mengecek apakah ada data cuti yang beririsan
        // $intersected = Absence::where('personnel_no', Auth::user()->personnel_no)
        //     ->intersectWith('2018-08-08', '2018-08-10')->first();
        // // var_dump($intersected->toArray());

        // var_dump($intersected->formattedPeriod);

        // // mencoba eager loading relationship
        // $with = \App\Models\AbsenceApproval::find(16);
        // $stage = $with->with('absence.stage')->first();
        // var_dump($stage->absence->stage->description);

        // // now menggunakan carbon
        // var_dump(\Carbon\Carbon::now()->toDateTimeString());

        // $intersected = \App\Models\TimeEvent::where('personnel_no', Auth::user()->personnel_no)
        // ->where('check_date', '2018-09-12')
        // ->first();
        // dd($intersected);

        // // ambil data persetujuan absence, WARNING nested relationship eager loading
        // $absenceApprovals = \App\Models\AbsenceApproval::where('regno', Auth::user()->personnel_no)
        //     ->with(['status:id,description', 'absence.user.employee', 'absence.absenceType'])
        //     ->get();
    
        // $aar = \App\Http\Resources\AbsenceApprovalResource::collection(
        //     \App\Models\AbsenceApproval::all()
        // );

        // $aar = new \App\Http\Resources\AbsenceApprovalResource(\App\Models\AbsenceApproval::find(27));
        // $aar = (\App\Http\Resources\AbsenceApprovalResource::collection(
        //     \App\Models\AbsenceApproval::where('regno', Auth::user()->personnel_no)
        //         ->with(['status:id,description', 'absence.user.employee', 'absence.absenceType'])
        //     )
        // );
        // return ($aar);
        
        // // mengecek apakah boleh melakukan lembur
        // var_dump(Auth::user()->employee->allowedForOvertime());


    }
}
