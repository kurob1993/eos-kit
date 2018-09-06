<?php

namespace App\Observers;

use App\Models\AttendanceQuota;
use App\Models\AttendanceQuotaApproval;
use App\Models\AttendanceQuotaQuota;
use App\Models\FlowStage;
use App\Models\Status;
use App\Notifications\LeaveSentToSapMessage;
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
        $c = $employee->minGeneralManagerBoss()->personnel_no;
        $director = $employee->director();

        /***********************************************************************
        * buat record AttendanceQuotaApproval yang terdiri dari dua persetujuan
        * pertama untuk Superintendent dan kedua untuk Manager
        * apabila karyawan tidak memiliki atasan Superintendent maka
        * hanya dibuat AttendanceQuotaApproval hanya untuk atasan Manager
        * dan apabila karyawan tidak memiliki Superintendent & Manager langsung
        * maka hanya dibuat AttendanceQuotaApproval hanya untuk General Manager
        ***********************************************************************/
            
        $firstAqa = new AttendanceQuotaApproval();
        $firstAqa->attendanceQuota_id = $attendanceQuota->id;
        $firstAqa->sequence = 1;
        $firstAqa->status_id = Status::firstStatus()->id;

        if (($a == $b) && ($a <> $c) && ($b <> $c) ) {
            
            // Jika tidak memliki atasan langsung Superintendent
            // Maka approvernya adalah Manager
            $firstAqa->regno = $a->personnel_no;
        
        } else if ( ($a == $b) && ($a == $c) && ($b == $c) ) {
            
            // Jika tidak memliki atasan langsung Superintendent & Manager
            // maka approvernya adalah General Manager
            $firstAqa->regno = $c->personnel_no;
        
        } else if ( ($a <> $b) && ($a <> $c) && ($b <> $c) ) {
        
            // Jika punya atasan langsung Superintendent & Manager
            // Membuat aqa untuk approver keduanya
            $secondAqa = new AttendanceQuotaApproval();
            $secondAqa->attendanceQuota_id = $attendanceQuota->id;
            $secondAqa->sequence = 2;
            $secondAqa->status_id = Status::firstStatus()->id;

            // approver pertama adalah atasan langsung Superintendent
            $firstAqa->regno = $a->personnel_no;
            // approver kedua adalah atasan langsung Manager
            $secondAqa->regno = $b->personnel_no;
        
        } else if ( ($a <> $b) && ($a <> $c) && ($b == $c) ) {

        
        } else {
        
            // Karyawan yang tidak memiliki atasan/atasannya adalah Direktur
            // dalam hal ini adalah General Manager ATAU
            // karyawan yang tidak memiliki atasan sama sekali
            // contoh: Deputi (UTOMO NUGROHO)

            // bypass regno menggunakan admin  dan sequence
            $admin = Role::retrieveAdmin();
            $firstAqa->regno = $admin->personnel_no;
            $firstAqa->sequence = 1;

            // menyimpan data persetujuan
            $firstAqa->save();

            // mengubah status menjadi approved
            $firstAqa->status_id = Status::ApproveStatus()->id;
            $firstAqa->text = 'Disetujui oleh Admin';

            // menyimpan perubahan agar mentrigger observer
            $firstAqa->save();
        }


        // JIKA karyawan tersebut mempunyai atasan langsung
        // maka simpan data atasan sebagai attendanceQuota approval
        // JIKA TIDAK mempunyai atasan langsung
        // maka attendanceQuota approval dibuat seolah-olah sudah disetujui
        // contoh: karyawan yang atasannya langsung direktur
        // atau deputi (UTOMO NUGROHO)
        if ($minSuperintendentBoss) {
            // menyimpan personnel_no dari closest boss
            $firstAqa->regno = $minSuperintendentBoss->personnel_no;

            // menyimpan data persetujuan
            $firstAqa->save();

        } else {

        }
    }

    public function updated(AttendanceQuota $attendanceQuota)
    {
        // apakah sudah selesai
        if ($attendanceQuota->isSuccess) {

            // to adalah karyawan yang mengajukan
            $to = $attendanceQuota->user()->first();

            // sistem mengirim email notifikasi
            $to->notify(new LeaveSentToSapMessage($attendanceQuota));
        }
    }
}
