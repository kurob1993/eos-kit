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
        // jika attendanceQuota adalah lembur tahunan / lembur besar (leaves)
        if ($attendanceQuota->isALeave) {

            // apakah tanggal lembur sudah pernah dilakukan sebelumnya (intersection)
            // HARUS DITAMBAHKAN APABILA dari masing-masing intersected statusnya DENIED
            // JIKA DENIED tidak termasuk intersected
            $intersected = AttendanceQuota::where('personnel_no', Auth::user()->personnel_no)
                ->leavesOnly()
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
            } else {
                // jika tidak, attendanceQuota adalah izin (permits)
            }
        }
    }

    public function created(AttendanceQuota $attendanceQuota)
    {
        // karyawan yang membuat attendanceQuota
        $employee = Auth::user()->employee()->first();

        // mendapatkan attendanceQuota_type_id dari kuota lembur yang digunakan
        $attendanceQuota_type_id = AttendanceQuotaQuota::activeAttendanceQuotaQuotaOf(
            Auth::user()
                ->personnel_no, $attendanceQuota->start_date, $attendanceQuota->end_date)
                ->first()
            ->attendanceQuota_type_id;

        // mendapatkan flow_id untuk attendanceQuotas dari file config
        // mencari sequence pertama dari flow_id diatas
        // mengembalikan flowstage dan mengakses stage_id
        $flow_id = config('emss.flows.attendanceQuotas');
        $stage_id = FlowStage::firstSequence($flow_id)->first()->stage_id;

        // mengisi attendanceQuota type dari server bukan dari request
        $attendanceQuota->attendanceQuota_type_id = $attendanceQuota_type_id;

        // mengisi stage id melalui mekanisme flow stage
        $attendanceQuota->stage_id = $stage_id;

        // simpan perubahan
        $attendanceQuota->save();

        // mencari atasan dari karyawan yang mengajukan attendanceQuotas
        $closestBoss = $employee->closestBoss();

        // mencari direktur dari karyawan yang mengajukan attendanceQuota
        $director = $employee->director();

        // buat record untuk attendanceQuota approval
        $attendanceQuota_approval = new AttendanceQuotaApproval();

        // foreign key pada attendanceQuota approval
        $attendanceQuota_approval->attendanceQuota_id = $attendanceQuota->id;

        // NEED TO IMPLEMENT FLOW STAGE
        // mengambil status dari firststatus
        $attendanceQuota_approval->sequence = 1;
        $attendanceQuota_approval->status_id = Status::firstStatus()->id;

        // JIKA karyawan tersebut mempunyai atasan langsung
        // maka simpan data atasan sebagai attendanceQuota approval
        // JIKA TIDAK mempunyai atasan langsung
        // maka attendanceQuota approval dibuat seolah-olah sudah disetujui
        // contoh: karyawan yang atasannya langsung direktur
        // atau deputi (UTOMO NUGROHO)
        if ($closestBoss) {
            // menyimpan personnel_no dari closest boss
            $attendanceQuota_approval->regno = $closestBoss->personnel_no;

            // menyimpan data persetujuan
            $attendanceQuota_approval->save();

        } else {
            // bypass regno menggunakan admin  dan sequence
            $admin = Role::retrieveAdmin();
            $attendanceQuota_approval->regno = $admin->personnel_no;
            $attendanceQuota_approval->sequence = 1;

            // menyimpan data persetujuan
            $attendanceQuota_approval->save();

            // mengubah status menjadi approved
            $attendanceQuota_approval->status_id = Status::ApproveStatus()->id;
            $attendanceQuota_approval->text = 'Disetujui oleh Admin';

            // menyimpan perubahan agar mentrigger observer
            $attendanceQuota_approval->save();
        }
    }

    public function updated(AttendanceQuota $attendanceQuota)
    {
        // apakah sudah selesai
        if ($attendanceQuota->isSuccess) {
            // mencari data kuota lembur yang dipakai
            $attendanceQuotaQuota = AttendanceQuotaQuota::activeAttendanceQuotaQuota($attendanceQuota->personnel_no)
                ->first();

            // menambah pemakaian lembur pada periode tersebut
            // seharusnya ada tabel history (many to many)
            // pemakaian lembur berkorelasi dengan attendanceQuota quota
            $attendanceQuotaQuota->deduction += $attendanceQuota->deduction;

            // simpan data kuota lembur
            $attendanceQuotaQuota->save();

            // to adalah karyawan yang mengajukan
            $to = $attendanceQuota->user()->first();

            // sistem mengirim email notifikasi
            $to->notify(new LeaveSentToSapMessage($attendanceQuota));
        }
    }
}
