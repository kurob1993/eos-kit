<?php

namespace App\Observers;

use App\Models\AttendanceQuota;
use App\Models\AttendanceQuotaApproval;
use App\Models\AttendanceQuotaQuota;
use App\Models\FlowStage;
use App\Models\Status;
use App\Notifications\OvertimeSentToSapMessage;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Auth;
use Session;

class AttendanceQuotaObserver
{
    public function creating(AttendanceQuota $attendanceQuota)
    {
        // apakah tanggal lembur sudah pernah dilakukan sebelumnya (intersection)
        // HARUS DITAMBAHKAN APABILA dari masing-masing intersected statusnya DENIED
        // JIKA DENIED tidak termasuk intersected
        $intersected = AttendanceQuota::where('personnel_no', Auth::user()->personnel_no)
            ->intersectWith($attendanceQuota->start_date, $attendanceQuota->end_date)
            ->first();
        if (sizeof($intersected) > 0) {
            Session::flash("flash_notification", [
                "level" => "danger",
                "message" => "Tidak dapat mengajukan lembur karena tanggal pengajuan "
                . "sudah pernah diajukan sebelumnya (ID " . $intersected->id . ": "
                . $intersected->formattedPeriod . ").",
            ]);
            return false;
        }
    }

    public function created(AttendanceQuota $attendanceQuota)
    {
        // karyawan yang membuat attendanceQuota
        $employee = Auth::user()->employee()->first();

        // mendapatkan flow_id untuk attendanceQuotas dari file config
        // mencari sequence pertama dari flow_id diatas
        // mengembalikan flowstage dan mengakses stage_id
        $flow_id = config('emss.flows.overtimes');
        $stage_id = FlowStage::firstSequence($flow_id)->first()->stage_id;

        // mengisi stage id melalui mekanisme flow stage
        $attendanceQuota->stage_id = $stage_id;

        // simpan perubahan
        $attendanceQuota->save();

        // mencari atasan & direktur dari karyawan yang mengajukan attendanceQuotas
        $a = $employee->minSuperintendentBoss()->personnel_no;
        $b = $employee->minManagerBoss()->personnel_no;
        $c = $employee->generalManagerBoss()->personnel_no;

        /***********************************************************************
        * Membuat record AttendanceQuotaApproval (aqa) dari skenario:
        * 1. Karyawan yang memiliki atasan Superintendent & Manager ->
        *    2 (dua) aqa dengan sequence Superintendent (1) & Manager (2)
        * 2. Karyawan yang memiliki atasan Superintendent tetapi tidak memiliki
        *    atasan Manager -> 2 (dua) aqa dengan sequence Superintendent (1)
        *    & General Manager (2)
        * 3. Karyawan yang tidak memiliki atasan Superintendent tetapi memiliki
        *    atasan Manager -> 1 (satu) aqa dengan sequence Manager (1)
        * 4. Karyawan yang tidak memiliki Superintendent & Manager ->
        *    1 (satu) aqa dengan sequence General Manager (1)
        ***********************************************************************/
        
        // minimal satu firstAqa untuk semua skenario
        $firstAqa = new AttendanceQuotaApproval();
        $firstAqa->attendance_quota_id = $attendanceQuota->id;
        $firstAqa->sequence = 1;
        $firstAqa->status_id = Status::firstStatus()->id;
        
        if ( ($a <> $b) && ($a <> $c) && ($b <> $c) ) {
        
            // Skenario 1
            // set approver pertama Superintendent (1)
            $firstAqa->regno = $a;

            // buat approver kedua yaitu Manager (2)
            $secondAqa = new AttendanceQuotaApproval();
            $secondAqa->attendance_quota_id = $attendanceQuota->id;
            $secondAqa->sequence = 2;
            $secondAqa->status_id = Status::firstStatus()->id;
            $secondAqa->regno = $b;

            // menyimpan approver
            $firstAqa->save();
            $secondAqa->save(); 
        
        } else if ( ($a <> $b ) && ($a <> $c) && ($b == $c) ) {
            
            // Skenario 2
            // set approver pertama Superintendent (1)
            $firstAqa->regno = $a;

            // buat approver kedua General Manager (2)
            $secondAqa = new AttendanceQuotaApproval();
            $secondAqa->attendance_quota_id = $attendanceQuota->id;
            $secondAqa->sequence = 2;
            $secondAqa->status_id = Status::firstStatus()->id;
            $secondAqa->regno = $c;

            // menyimpan approver
            $firstAqa->save();
            $secondAqa->save(); 

        } else if ( ($a == $b) && ($a <> $c) && ($b <> $c) ) {

            // Skenario 3
            // set approver pertama Manager (1)
            $firstAqa->regno = $b;
            
            // menyimpan approver
            $firstAqa->save();

        } else if ( ($a == $b) && ($a == $c) && ($b == $c) ) {

            // Skenario 4
            // set approver pertama General Manager (1)
            $firstAqa->regno = $c;

            // menyimpan approver
            $firstAqa->save();

        }
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
}
