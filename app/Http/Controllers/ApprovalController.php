<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;
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

class ApprovalController extends Controller
{

    public function approval()
    {
        return view('dashboards.approval');
    }

    public function leaveApproval(Request $request)
    {
        // ambil data persetujuan absence, WARNING nested relationship eager loading
        $leaveApprovals = AbsenceApproval::ofLoggedUser()
            ->leavesOnly()
            ->with([
                'status:id,description',
                'absence',
                'absence.user',
                'absence.stage',
                'absence.employee:personnel_no,name',
                'absence.absenceType',
                'absence.absenceApprovals'
            ]);

        if (is_numeric($request->input('stage_id')))
            $leaveApprovals->whereStageId('absence', $request->input('stage_id'));

        // response untuk datatables absences approval
        if ($request->ajax()) {

            return Datatables::of($leaveApprovals)
                ->editColumn('absence.start_date', function (AbsenceApproval $aa) {
                    return $aa->absence->formatted_start_date;
                })
                ->editColumn('absence.end_date', function (AbsenceApproval $aa) {
                    return $aa->absence->formatted_end_date;
                })
                ->editColumn('absence.user.name', function (AbsenceApproval $aa) {
                    return $aa->absence->user['name'];
                })
                ->editColumn('absence.absence_approvals', function (AbsenceApproval $aa) {
                    $approvals = $aa->absence->absenceApprovals;
                    $a = '';
                    foreach ($approvals as $approval)
                        $a = $a . view('layouts._personnel-no-with-name', [
                            'personnel_no' => $approval->regno,
                            'employee_name' => $approval->employee->name
                        ]) . '<br />';
                    return $a;
                })
                ->addColumn('duration', function (AbsenceApproval $aa) {
                    return $aa->absence->duration . ' hari';
                })
                ->addColumn('action', function (AbsenceApproval $aa) {
                    if ($aa->absence->is_waiting_approval) {
                        // apakah stage-nya: waiting approval
                        return view('components._action-approval', [
                            'model' => $aa,
                            'approve_url' => route('dashboards.approve', [
                                'id' => $aa->id, 'approval' => 'leave'
                            ]),
                            'reject_url' => route('dashboards.reject', [
                                'id' => $aa->id, 'approval' => 'leave'
                            ]),
                        ]);
                    }
                })
                ->escapeColumns([])
                ->make(true);
        }
    }

    public function permitApproval(Request $request)
    {
        // ambil data persetujuan absence, WARNING nested relationship eager loading
        $absenceApprovals = AbsenceApproval::ofLoggedUser()
            ->excludeLeaves()
            ->with([
                'status:id,description',
                'permit',
                'permit.user',
                'permit.stage',
                'permit.employee:personnel_no,name',
                'permit.permitType',
                'permit.permitApprovals',
            ]);

        if (is_numeric($request->input('stage_id')))
            $absenceApprovals->whereStageId('permit', $request->input('stage_id'));

        $absenceApprovals = $absenceApprovals->get();

        // ambil data persetujuan attendance, WARNING nested relationship eager loading
        $attendanceApprovals = AttendanceApproval::ofLoggedUser()
            ->with([
                'status:id,description',
                'permit',
                'permit.stage',
                'permit.employee:personnel_no,name',
                'permit.permitType',
                'permit.permitApprovals',
            ]);

        if (is_numeric($request->input('stage_id')))
            $attendanceApprovals->whereStageId('permit', $request->input('stage_id'));

        $permitApprovals = $attendanceApprovals->get()->merge($absenceApprovals);

        // response untuk datatables permits approval
        if ($request->ajax()) {

            return Datatables::of($permitApprovals)
                ->editColumn('permit.start_date', function ($aa) {
                    return $aa->permit->formatted_start_date;
                })
                ->editColumn('permit.end_date', function ($aa) {
                    return $aa->permit->formatted_end_date;
                })
                ->editColumn('permit.user.name', function ($aa) {
                    return $aa->permit->user['name'];
                })
                ->editColumn('permit.permit_approvals', function ($aa) {
                    $approvals = $aa->permit->permitApprovals;
                    $a = '';
                    foreach ($approvals as $approval)
                        $a = $a . view('layouts._personnel-no-with-name', [
                            'personnel_no' => $approval->regno,
                            'employee_name' => $approval->employee->name
                        ]) . '<br />';
                    return $a;
                })
                ->addColumn('duration', function ($aa) {
                    return $aa->permit->duration . ' hari';
                })
                ->editColumn('permit.attachment', function ($aa) {
                    if (str_is("*pdf", $aa->permit->attachment)) {
                        $x = '<a href="' . Storage::url($aa->permit->attachment) . '"
                    class="btn btn-primary" target="_blank">View</a>';
                    } else {
                        $x = '<img class="center-block img-responsive"
                    src="' . Storage::url($aa->permit->attachment) . '" alt="">';
                    }
                    return $x;
                })
                ->addColumn('action', function ($aa) {
                    if ($aa->permit->is_waiting_approval) {
                        if ($aa->permit instanceof Absence)
                            $approval = 'absence';
                        else if ($aa->permit instanceof Attendance)
                            $approval = 'attendance';
                        // apakah stage-nya: waiting approval
                        return view('components._action-approval', [
                            'model' => $aa,
                            'approve_url' => route('dashboards.approve', [
                                'id' => $aa->id, 'approval' => $approval
                            ]),
                            'reject_url' => route('dashboards.reject', [
                                'id' => $aa->id, 'approval' => $approval
                            ]),
                        ]);
                    }
                })
                ->escapeColumns([])
                ->make(true);
        }
    }

    public function timeEventApproval(Request $request)
    {

        // ambil data persetujuan timeEvent, WARNING nested relationship eager loading
        $timeEventApprovals = TimeEventApproval::ofLoggedUser()
            ->with([
                'status:id,description',
                'timeEvent',
                'timeEvent.user',
                'timeEvent.stage',
                'timeEvent.employee:personnel_no,name',
                'timeEvent.timeEventType',
                'timeEvent.timeEventApprovals',
            ]);

        if (is_numeric($request->input('stage_id')))
            $timeEventApprovals->whereStageId('timeEvent', $request->input('stage_id'));

        // response untuk datatables absences approval
        if ($request->ajax()) {

            return Datatables::of($timeEventApprovals)
                ->editColumn('time_event.check_date', function (TimeEventApproval $aa) {
                    return $aa->timeEvent->formattedCheckDate;
                })
                ->editColumn('time_event.check_time', function (TimeEventApproval $aa) {
                    return $aa->timeEvent->check_time;
                })
                ->editColumn('time_event.user.name', function (TimeEventApproval $aa) {
                    return $aa->timeEvent->user['name'];
                })
                ->editColumn('time_event.time_event_approvals', function (TimeEventApproval $aa) {
                    $approvals = $aa->timeEvent->TimeEventApprovals;
                    $a = '';
                    foreach ($approvals as $approval)
                        $a = $a . view('layouts._personnel-no-with-name', [
                            'personnel_no' => $approval->regno,
                            'employee_name' => $approval->employee->name
                        ]) . '<br />';
                    return $a;
                })
                ->addColumn('action', function (TimeEventApproval $aa) {
                    if ($aa->timeEvent->is_waiting_approval) {
                        // apakah stage-nya: waiting approval
                        return view('components._action-approval', [
                            'model' => $aa,
                            'approve_url' => route('dashboards.approve', [
                                'id' => $aa->id, 'approval' => 'time_event'
                            ]),
                            'reject_url' => route('dashboards.reject', [
                                'id' => $aa->id, 'approval' => 'time_event'
                            ]),
                        ]);
                    }
                })
                ->escapeColumns([])
                ->make(true);
        }
    }

    public function overtimeApproval(Request $request)
    {
        // ambil data persetujuan attendanceQuota, WARNING nested relationship eager loading
        $overtimeApprovals = AttendanceQuotaApproval::ofLoggedUser()
            ->with([
                'status:id,description',
                'attendanceQuota',
                'attendanceQuota.user',
                'attendanceQuota.stage',
                'attendanceQuota.employee:personnel_no,name',
                'attendanceQuota.attendanceQuotaType',
                'attendanceQuota.attendanceQuotaApproval',
                'attendanceQuota.overtimeReason',
            ]);

        if (is_numeric($request->input('stage_id')))
            $overtimeApprovals->whereStageId('attendanceQuota', $request->input('stage_id'));

        // response untuk datatables absences approval
        if ($request->ajax()) {

            return Datatables::of($overtimeApprovals)
                ->editColumn('attendance_quota.start_date', function (AttendanceQuotaApproval $aa) {
                    return $aa->attendanceQuota->start_date;
                })
                ->editColumn('attendance_quota.end_date', function (AttendanceQuotaApproval $aa) {
                    return $aa->attendanceQuota->end_date;
                })
                ->editColumn('attendance_quota.user.name', function (AttendanceQuotaApproval $aa) {
                    return $aa->attendanceQuota->user['name'];
                })
                ->editColumn('attendance_quota.attendance_quota_approval', function (AttendanceQuotaApproval $aa) {
                    $approvals = $aa->attendanceQuota->attendanceQuotaApproval;
                    $a = '';
                    foreach ($approvals as $approval) {
                        if ($approval->is_waiting)
                            $a = $a . '<i class="fa fa-clock-o"></i>&nbsp;';
                        else if ($approval->is_approved) {
                            $a = $a . '<i class="fa fa-check text-success"></i>&nbsp;';
                            $a = $a . $approval->updated_at . '&nbsp;';
                        } else if ($approval->is_rejected)
                            $a = $a . '<i class="fa fa-times text-danger"></i>&nbsp;';

                        $a = $a . view('layouts._personnel-no-with-name', [
                            'personnel_no' => $approval->regno,
                            'employee_name' => $approval->employee->name
                        ]) . '<br />';
                    }
                    return $a;
                })
                ->addColumn('duration', function (AttendanceQuotaApproval $aa) {
                    return $aa->attendanceQuota->duration . ' menit';
                })
                ->addColumn('action', function (AttendanceQuotaApproval $aa) {
                    if (
                        $aa->attendanceQuota->is_waiting_approval &&
                        $aa->isWaiting
                    ) {
                        // apakah stage-nya: waiting approval
                        return view('components._action-approval', [
                            'model' => $aa,
                            'approve_url' => route('dashboards.approve', [
                                'id' => $aa->id, 'approval' => 'overtime'
                            ]),
                            'reject_url' => route('dashboards.reject', [
                                'id' => $aa->id, 'approval' => 'overtime'
                            ]),
                        ]);
                    }
                })
                ->escapeColumns([])
                ->make(true);
        }
    }

    public function approve(Request $request, $approval, $id)
    {
        // poor database design
        switch ($approval) {
            case 'leave':
                $approved = AbsenceApproval::find($id);
                $moduleText = config('emss.modules.leaves.text');
                // delegasi
                $this->storeToDelegation($approval, $id);
                break;
            case 'absence':
                $approved = AbsenceApproval::find($id);
                $moduleText = config('emss.modules.permits.text');
                // delegasi
                $this->storeToDelegation($approval, $id);
                break;
            case 'attendance':
                $approved = AttendanceApproval::find($id);
                $moduleText = config('emss.modules.permits.text');
                // delegasi
                $this->storeToDelegation($approval, $id);
                break;
            case 'time_event':
                $approved = TimeEventApproval::find($id);
                $moduleText = config('emss.modules.time_events.text');
                break;
            case 'overtime':
                $approved = AttendanceQuotaApproval::find($id);
                $moduleText = config('emss.modules.overtimes.text');
                break;
        }

        $approved->status_id = Status::approveStatus()->id;
        if (!$approved->save()) {
            // kembali lagi jika gagal
            return redirect()->back();
        }

        // tampilkan pesan bahwa telah berhasil menyetujui
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menyetujui " . $moduleText
        ]);

        // kembali lagi ke dashboard employee
        return redirect()->route('dashboards.approval');
    }

    public function reject(Request $request, $approval, $id)
    {
        // poor database design
        switch ($approval) {
            case 'leave':
                $approved = AbsenceApproval::find($id);
                $moduleText = config('emss.modules.leaves.text');
                break;
            case 'absence':
                $approved = AbsenceApproval::find($id);
                $moduleText = config('emss.modules.permits.text');
                break;
            case 'attendance':
                $approved = AttendanceApproval::find($id);
                $moduleText = config('emss.modules.permits.text');
                break;
            case 'time_event':
                $approved = TimeEventApproval::find($id);
                $moduleText = config('emss.modules.time_events.text');
                break;
            case 'overtime':
                $approved = AttendanceQuotaApproval::find($id);
                $moduleText = config('emss.modules.overtimes.text');
                break;
        }

        if (!$approved->update($request->all()
            + ['status_id' => Status::rejectStatus()->id])) {
            // kembali lagi jika gagal
            return redirect()->back();
        }

        // tampilkan pesan bahwa telah berhasil menolak
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => "Berhasil menolak " . $moduleText
        ]);

        // kembali lagi ke dashboard employee
        return redirect()->route('dashboards.approval');
    }

    protected function approvalAll($approval, $text, $status_id)
    {
        // poor database design
        switch ($approval) {
            case 'leave':
                $approvals = AbsenceApproval::ofLoggedUser()
                    ->whereStageIsWaitingApproval('absence')
                    ->leavesOnly()
                    ->get();
                $moduleText = config('emss.modules.leaves.text');
                $this->storeToDelegationForAll('leave',$approvals);
                break;
            case 'permit':
                $approvals = AbsenceApproval::ofLoggedUser()
                    ->whereStageIsWaitingApproval('absence')
                    ->get();
                $this->storeToDelegationForAll('absence',$approvals);

                $approvals = AttendanceApproval::ofLoggedUser()
                    ->whereStageIsWaitingApproval('attendance')
                    ->get()
                    ->merge($approvals);
                $this->storeToDelegationForAll('attendance',$approvals);

                $moduleText = config('emss.modules.permits.text');
                break;
            case 'time_event':
                $approvals = TimeEventApproval::ofLoggedUser()
                    ->whereStageIsWaitingApproval('timeEvent')
                    ->get();
                $moduleText = config('emss.modules.time_events.text');
                break;
            case 'overtime':
                $approvals = AttendanceQuotaApproval::ofLoggedUser()
                    ->whereStageIsWaitingApproval('attendanceQuota')
                    ->get();
                $moduleText = config('emss.modules.overtimes.text');
                break;
        }

        // counter untuk berhasil dan tidak berhasil
        $success_count = $error_count = 0;

        // Ubah status persetujuan dari collection
        $approvals->each(function ($a) use (&$success_count, &$error_count, $text, $status_id) {
            $a->status_id = $status_id;
            $a->text = $text;
            ($a->save()) ? $success_count++ : $error_count++;
        });

        // tampilkan pesan bahwa telah berhasil setuju semua
        Session::flash("flash_notification", [
            "level" => "success",
            "message" => sprintf(
                "Berhasil Menyetujui %s. Berhasil:%u & gagal:%u.",
                $moduleText,
                $success_count,
                $error_count
            )
        ]);

        // kembali lagi ke dashboard employee
        return redirect()->route('dashboards.approval');
    }

    public function approveAll(Request $request)
    {
        $result = $this->approvalAll(
            $request->input("approval"),
            $request->input("text"),
            Status::approveStatus()->id
        );

        return $result;
    }

    public function rejectAll(Request $request)
    {
        $result = $this->approvalAll(
            $request->input("approval"),
            $request->input("text"),
            Status::rejectStatus()->id
        );

        return $result;
    }

    public function storeToDelegation($module,$id)
    {
        $approved = null;
        switch ($module) {
            case 'leave':
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

            case 'absence':
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
    
    public function storeToDelegationForAll($module,$data){
        foreach ($data as $key => $value) {
           $this->storeToDelegation($module,$value->id);
        }
    }
}
