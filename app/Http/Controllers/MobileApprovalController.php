<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsenceApproval;
use App\Models\AttendanceApproval;
use App\Models\TimeEventApproval;
use App\Models\AttendanceQuotaApproval;
use App\Models\Status;
use App\Models\Absence;
use App\Models\Attendance;
use App\Models\Transition;
use Session;
use Storage;

class MobileApprovalController extends Controller
{
    public function approve(Request $request)
    {
        // poor database design
        switch ($request->approval) {
            case 'leave':
                $approved = AbsenceApproval::find($request->id);
                $moduleText = config('emss.modules.leaves.text');
                // delegasi
                $this->storeToDelegation($request->approval, $request->id);
                break;
            case 'absence':
                $approved = AbsenceApproval::find($request->id);
                $moduleText = config('emss.modules.permits.text');
                // delegasi
                $this->storeToDelegation($request->approval, $request->id);
                break;
            case 'attendance':
                $approved = AttendanceApproval::find($request->id);
                $moduleText = config('emss.modules.permits.text');
                // delegasi
                $this->storeToDelegation($request->approval, $request->id);
                break;
            case 'time_event':
                $approved = TimeEventApproval::find($request->id);
                $moduleText = config('emss.modules.time_events.text');
                break;
            case 'overtime':
                $approved = AttendanceQuotaApproval::find($request->id);
                $moduleText = config('emss.modules.overtimes.text');
                break;
        }

        $approved->status_id = Status::approveStatus()->id;
        $approved->save();

        return redirect()->back();
        
    }

    public function storeToDelegation($module,$id)
    {
        $approved = null;
        switch ($module) {
            case 'leave':
                $approved = AbsenceApproval::find($id);
                if($approved){
                    $approved =  $approved->absence;
                    $start_date = $approved->start_date->toDateString();
                    $end_date = $approved->end_date->toDateString();
                    $strucdisp = $approved->employee->StructDisp->first();

                    $transition = Transition::where('abbr_jobs',$strucdisp->emp_hrp1000_s_short)
                        ->where('start_date',$start_date)
                        ->where('end_date',$end_date);
                }
                break;

            case 'absence':
                $approved = AbsenceApproval::find($id);
                if($approved){
                    $approved =  $approved->absence;
                    $start_date = $approved->start_date->toDateString();
                    $end_date = $approved->end_date->toDateString();
                    $strucdisp = $approved->employee->StructDisp->first();

                    $transition = Transition::where('abbr_jobs',$strucdisp->emp_hrp1000_s_short)
                        ->where('start_date',$start_date)
                        ->where('end_date',$end_date);
                }
                break;

            case 'attendance':
                $approved = AttendanceApproval::find($id);
                if($approved){
                    $approved =  $approved->attendance;
                    $start_date = $approved->start_date->toDateString();
                    $end_date = $approved->end_date->toDateString();
                    $strucdisp = $approved->employee->StructDisp->first();

                    $transition = Transition::where('abbr_jobs',$strucdisp->emp_hrp1000_s_short)
                        ->where('start_date',$start_date)
                        ->where('end_date',$end_date);
                }
                break;
            
            default:
                # code...
                break;
        }

        if(!$approved){
            // tampilkan pesan bahwa telah berhasil menyetujui
            Session::flash("flash_notification", [
                "level" => "warning",
                "message" => "Gagal approve dikarnakan data delegasi tidak dapat di simpan."
            ]);
            return redirect()->back();
        }
        $transition->update(['actived_at'=>date('Y-m-d H:i:s')]);
    }

    public function reject(Request $request)
    {
        // poor database design
        switch ($request->approval) {
            case 'leave':
                $approved = AbsenceApproval::find($request->id);
                $moduleText = config('emss.modules.leaves.text');
                break;
            case 'absence':
                $approved = AbsenceApproval::find($request->id);
                $moduleText = config('emss.modules.permits.text');
                break;
            case 'attendance':
                $approved = AttendanceApproval::find($request->id);
                $moduleText = config('emss.modules.permits.text');
                break;
            case 'time_event':
                $approved = TimeEventApproval::find($request->id);
                $moduleText = config('emss.modules.time_events.text');
                break;
            case 'overtime':
                $approved = AttendanceQuotaApproval::find($request->id);
                $moduleText = config('emss.modules.overtimes.text');
                break;
        }

        $approved->status_id = Status::rejectStatus()->id;
        $approved->save();

        return redirect()->back();
    }
}
