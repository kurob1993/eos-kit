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
        // jika attendance adalah izin tahunan / izin besar (leaves)
        if ($attendance->isAPermit) {
            // ambil kuota izin berdasarkan tanggal mulai & berakhir izin
            $attendance_quota = AttendanceQuota::activeAttendanceQuotaOf(
                Auth::user()->personnel_no, $attendance->start_date, $attendance->end_date)
                ->first();
    
            // apakah sisa izin (balance)  kurang dari pengajuan (deduction)?
            if ($attendance_quota->balance < $attendance->deduction) {
                Session::flash("flash_notification", [
                    "level" => "danger",
                    "message" => "Tidak dapat mengajukan izin karena jumlah pengajuan izin "
                    . "melebihi sisa izin periode saat ini "
                    . "(Sisa Cuti =" . $attendance_quota->balance . " < pengajuan izin="
                    . $attendance->deduction . "). Silahkan ajukan izin dengan jumlah "
                    . "kurang dari/sama dengan sisa izin.",
                ]);
                return false;
            }
            // apakah tanggal izin sudah pernah dilakukan sebelumnya (intersection)
            // HARUS DITAMBAHKAN APABILA dari masing-masing intersected statusnya DENIED
            // JIKA DENIED tidak termasuk intersected
            $intersected = Attendance::where('personnel_no', Auth::user()->personnel_no)
                ->leavesOnly()
                ->intersectWith($attendance->start_date, $attendance->end_date)
                ->first();
            if (sizeof($intersected) > 0) {
                Session::flash("flash_notification", [
                    "level" => "danger",
                    "message" => "Tidak dapat mengajukan izin karena tanggal pengajuan "
                    . "sudah pernah diajukan sebelumnya (ID " . $intersected->id . ": "
                    . $intersected->formattedPeriod . ").",
                ]);
                return false;
            } else {
                // jika tidak, attendance adalah izin (permits)
            }
        }
    }

    public function created(Attendance $attendance)
    {
        // karyawan yang membuat attendance
        $employee = Auth::user()->employee()->first();

        // mendapatkan attendance_type_id dari kuota izin yang digunakan
        $attendance_type_id = AttendanceQuota::activeAttendanceQuotaOf(
            Auth::user()
                ->personnel_no, $attendance->start_date, $attendance->end_date)
                ->first()
            ->attendance_type_id;

        // mendapatkan flow_id untuk attendances dari file config
        // mencari sequence pertama dari flow_id diatas
        // mengembalikan flowstage dan mengakses stage_id
        $flow_id = config('emss.flows.attendances');
        $stage_id = FlowStage::firstSequence($flow_id)->first()->stage_id;

        // mengisi attendance type dari server bukan dari request
        $attendance->attendance_type_id = $attendance_type_id;

        // mengisi stage id melalui mekanisme flow stage
        $attendance->stage_id = $stage_id;

        // simpan perubahan
        $attendance->save();

        // mencari atasan dari karyawan yang mengajukan attendances
        $closestBoss = $employee->closestBoss();

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
            // mencari data kuota izin yang dipakai
            $attendanceQuota = AttendanceQuota::activeAttendanceQuota($attendance->personnel_no)
                ->first();

            // menambah pemakaian izin pada periode tersebut
            // seharusnya ada tabel history (many to many)
            // pemakaian izin berkorelasi dengan attendance quota
            $attendanceQuota->deduction += $attendance->deduction;

            // simpan data kuota izin
            $attendanceQuota->save();

            // to adalah karyawan yang mengajukan
            $to = $attendance->user()->first();

            // sistem mengirim email notifikasi
            $to->notify(new PermitSentToSapMessage($attendance));
        }
    }
}
