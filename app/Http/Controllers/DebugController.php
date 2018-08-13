<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreAbsenceRequest;
use App\Models\Absence;
use Illuminate\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class DebugController extends Controller
{
    public function debug()
    {
        // // mencari flow berdasarkan flow_id
        // $flow = \App\Models\Flow::find($flow_id);
        // var_dump($flow->toArray());
        // // mengakses stages melalui many to many relationship melalui pivot table
        // foreach ($flow->stages as $stage) {
        //   var_dump($stage->pivot->toArray());
        // }

        // // mencari first sequence terhadap flow_id
        // $flow_id = \Config::get('emss.flows.absences');
        // $flow = \App\Models\FlowStage::firstSequence($flow_id);
        // var_dump($flow->toArray());

        // // mengecek kuota absence terhadap suatu rentang tanggal
        // $absence_type_id = \App\Models\AbsenceQuota::activeAbsenceQuotaOf(
        //   11725, '2018-07-13', '2018-07-13')
        //   ->first()->absenceType()->first()->toArray();
        // dd($absence_type_id);

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

        // // mencari atasan-atasan dari personnel_no
        // $bosses = \App\Models\StructDisp::BossesOf('11725');
        // var_dump($bosses->toArray());

        // // mencari karyawan berdasarkan golongan
        // $subgroup_struct = \App\Models\StructDisp::subgroupStructOf('ES');
        // var_dump($subgroup_struct->toArray());
    
        // // mencari data karyawan untuk user yang sudah login
        // var_dump(Auth::user()->employee()->first()->toArray());

        // // mencari semua absences dengan global scope
        // var_dump(\App\Models\Absence::get()->toArray());

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
        // var_dump(Auth::user()->employee()->first()->hasSubordinate());

        //Melihat return firstStatus pada model status
        // $status = \App\Models\Status::firstStatus()->id;
        // var_dump($status);

        // // mencoba reject dan approve status
        // $status = \App\Models\Status::approveStatus()->id;
        // var_dump($status);
        // $status = \App\Models\Status::rejectStatus()->id;
        // var_dump($status);

        // nested relationship eager loading
        $absenceApprovals = \App\Models\AbsenceApproval::where('regno', Auth::user()->personnel_no)
        ->with(['status:id,description', 'absence.user.employee']);
        
        var_dump($absenceApprovals->first()->toArray());
    }
}
