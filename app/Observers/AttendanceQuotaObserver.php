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

        // mencari atasan dari karyawan yang mengajukan attendanceQuotas
        $minSuperintendentBoss = $employee->minSuperintendentBoss();
        $minManagerBoss = $employee->minManagerBoss();

        // mencari direktur dari karyawan yang mengajukan attendanceQuota
        $director = $employee->director();

        // buat record untuk attendanceQuota approval
        $aqa = new AttendanceQuotaApproval();
        // foreign key pada attendanceQuota approval
        $aqa->attendanceQuota_id = $attendanceQuota->id;
        // NEED TO IMPLEMENT FLOW STAGE
        // mengambil status dari firststatus
        $aqa->sequence = 1;
        $aqa->status_id = Status::firstStatus()->id;

        // JIKA karyawan tersebut mempunyai atasan langsung
        // maka simpan data atasan sebagai attendanceQuota approval
        // JIKA TIDAK mempunyai atasan langsung
        // maka attendanceQuota approval dibuat seolah-olah sudah disetujui
        // contoh: karyawan yang atasannya langsung direktur
        // atau deputi (UTOMO NUGROHO)
        if ($minSuperintendentBoss) {
            // menyimpan personnel_no dari closest boss
            $aqa->regno = $minSuperintendentBoss->personnel_no;

            // menyimpan data persetujuan
            $aqa->save();

        } else {
            // bypass regno menggunakan admin  dan sequence
            $admin = Role::retrieveAdmin();
            $aqa->regno = $admin->personnel_no;
            $aqa->sequence = 1;

            // menyimpan data persetujuan
            $aqa->save();

            // mengubah status menjadi approved
            $aqa->status_id = Status::ApproveStatus()->id;
            $aqa->text = 'Disetujui oleh Admin';

            // menyimpan perubahan agar mentrigger observer
            $aqa->save();
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
