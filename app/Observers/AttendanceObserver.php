<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Models\AttendanceApproval;
use App\Models\AttendanceQuota;
use App\Models\FlowStage;
use App\Models\Status;
use App\Notifications\PermitSentToSapMessage;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Auth;
use Session;

class AttendanceObserver
{
    public function creating(Attendance $attendance)
    {
        // apakah tanggal izin sudah pernah dilakukan sebelumnya (intersection)
        // HARUS DITAMBAHKAN APABILA dari masing-masing intersected statusnya DENIED
        // JIKA DENIED tidak termasuk intersected
        $intersected = Attendance::where('personnel_no', Auth::user()->personnel_no)
            ->intersectWith($attendance->start_date, $attendance->end_date)
            ->first();
        if ((sizeof($intersected) > 0) && !$attendance->is_denied) {
            Session::flash("flash_notification", [
                "level" => "danger",
                "message" => "Tidak dapat mengajukan izin karena tanggal pengajuan "
                . "sudah pernah diajukan sebelumnya (ID " . $intersected->id . ": "
                . $intersected->formattedPeriod . ").",
            ]);
            return false;
        }
    }

    public function created(Attendance $attendance)
    {
        // karyawan yang membuat attendance
        $employee = Auth::user()->employee()->first();

        // mendapatkan flow_id untuk attendances dari file config
        // mencari sequence pertama dari flow_id diatas
        // mengembalikan flowstage dan mengakses stage_id
        $flow_id = config('emss.flows.attendances');
        $stage_id = FlowStage::firstSequence($flow_id)->first()->stage_id;

        // mengisi stage id melalui mekanisme flow stage
        $attendance->stage_id = $stage_id;

        // simpan perubahan
        $attendance->save();

        // mencari atasan dari karyawan yang mengajukan attendances
        $closestBoss = $employee->minSuperintendentBoss();

        // mencari direktur dari karyawan yang mengajukan attendance
        $director = $employee->director();

        // buat record untuk attendance approval
        $attendance_approval = new AttendanceApproval();

        // foreign key pada attendance approval
        $attendance_approval->attendance_id = $attendance->id;

        // NEED TO IMPLEMENT FLOW STAGE
        // mengambil status dari firststatus
        $attendance_approval->sequence = 1;
        $attendance_approval->status_id = Status::firstStatus()->id;

        // JIKA karyawan tersebut mempunyai atasan langsung
        // maka simpan data atasan sebagai attendance approval
        // JIKA TIDAK mempunyai atasan langsung
        // maka attendance approval dibuat seolah-olah sudah disetujui
        // contoh: karyawan yang atasannya langsung direktur
        // atau deputi (UTOMO NUGROHO)
        if ($closestBoss) {
            // menyimpan personnel_no dari closest boss
            $attendance_approval->regno = $closestBoss->personnel_no;

            // menyimpan data persetujuan
            $attendance_approval->save();

        } else {
            // bypass regno menggunakan admin  dan sequence
            $admin = Role::retrieveAdmin();
            $attendance_approval->regno = $admin->personnel_no;
            $attendance_approval->sequence = 1;

            // menyimpan data persetujuan
            $attendance_approval->save();

            // mengubah status menjadi approved
            $attendance_approval->status_id = Status::ApproveStatus()->id;
            $attendance_approval->text = 'Disetujui oleh Admin';

            // menyimpan perubahan agar mentrigger observer
            $attendance_approval->save();
        }
    }

    public function updated(Attendance $attendance)
    {
        // apakah sudah selesai
        if ($attendance->isSuccess) {
            // to adalah karyawan yang mengajukan
            $to = $attendance->user()->first();

            // sistem mengirim email notifikasi
            $to->notify(new PermitSentToSapMessage($attendance));
        }
    }
}
