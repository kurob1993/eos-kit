<?php

namespace App\Observers;

use Session;
use App\Models\AttendanceQuota;
use App\Models\AttendanceQuotaApproval;
use App\Models\CostCenter;
use App\Models\FlowStage;
use App\Models\Status;
use App\Notifications\OvertimeSentToSapMessage;
use App\Notifications\OvertimeDeletedMessage;

class AttendanceQuotaObserver
{
    public function creating(AttendanceQuota $attendanceQuota)
    {
        $personnel_no = $attendanceQuota->personnel_no;

        // apakah tanggal lembur sudah pernah dilakukan sebelumnya (intersection)
        // HARUS DITAMBAHKAN APABILA dari masing-masing intersected statusnya DENIED
        // JIKA DENIED tidak termasuk intersected
        $intersected = AttendanceQuota::where('personnel_no', $personnel_no)
            ->intersectWith($attendanceQuota->start_date, $attendanceQuota->end_date)
            ->first();

        if ( $intersected && !$intersected->is_denied) {
            Session::flash("flash_notification", [
                "level" => "danger",
                "message" => "Tidak dapat mengajukan lembur karena tanggal pengajuan "
                . "sudah pernah diajukan sebelumnya (ID " . $intersected->id . ": "
                . $intersected->period . ").",
            ]);
            return false;
        }
    }

    public function created(AttendanceQuota $attendanceQuota)
    {
        // mendapatkan flow_id untuk attendanceQuotas dari file config
        // mencari sequence pertama dari flow_id diatas
        // mengembalikan flowstage dan mengakses stage_id
        $flow_id = config('emss.flows.overtimes');
        $stage_id = FlowStage::firstSequence($flow_id)->first()->stage_id;

        // mengisi stage id melalui mekanisme flow stage
        $attendanceQuota->stage_id = $stage_id;
        $attendanceQuota->save();

        $cc = CostCenter::find($attendanceQuota->cost_center_id);

        $firstAqa = new AttendanceQuotaApproval();
        $firstAqa->attendance_quota_id = $attendanceQuota->id;
        $firstAqa->sequence = 1;
        $firstAqa->status_id = Status::firstStatus()->id;
        $firstAqa->regno = $cc->employee->personnel_no;
        $firstAqa->save();

    }

    public function updated(AttendanceQuota $attendanceQuota)
    {
        // apakah sudah selesai
        if ($attendanceQuota->isSuccess) {
 
            // to adalah karyawan yang mengajukan
            $to = $attendanceQuota->user()->first();

            // sistem mengirim email notifikasi
            $to->notify(new OvertimeSentToSapMessage($attendanceQuota));
        }
    }

    public function deleting(AttendanceQuota $attendanceQuota)
    {
        $approvals = $attendanceQuota->attendanceQuotaApproval;

        // hapus semua approval terkait lembur
        foreach ($approvals as $approval)
            $approval->delete();
            // sistem mengirim notifikasi
        $to = $attendanceQuota->user;
        $to->notify(new OvertimeDeletedMessage($attendanceQuota));
    }
}
