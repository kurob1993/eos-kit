<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\Absence;
use App\Models\AbsenceApproval;
use App\Models\AbsenceQuota;
use App\Models\FlowStage;
use App\Models\Status;
use App\Notifications\LeaveSentToSapMessage;
use App\User;
use App\Role;

class AbsenceObserver
{
    public function creating(Absence $absence)
    {
        // ambil kuota cuti berdasarkan tanggal mulai & berakhir cuti
        $absence_quota = AbsenceQuota::activeAbsenceQuotaOf(
            Auth::user()->personnel_no, $absence->start_date, $absence->end_date)
            ->first();

        // apakah sisa cuti (balance)  kurang dari pengajuan (deduction)?
        if ($absence_quota->balance < $absence->deduction) {
            Session::flash("flash_notification", [
                "level" => "danger",
                "message" => "Tidak dapat mengajukan cuti karena jumlah pengajuan cuti "
                . "melebihi sisa cuti periode saat ini "
                . "(Sisa Cuti =" . $absence_quota->balance . " < pengajuan cuti="
                . $absence->deduction . "). Silahkan ajukan cuti dengan jumlah "
                . "kurang dari/sama dengan sisa cuti.",
            ]);
            return false;
        }

        // apakah tanggal cuti sudah pernah dilakukan sebelumnya (intersection)
        $intersected = Absence::where('personnel_no', Auth::user()->personnel_no)
            ->intersectWith($absence->start_date, $absence->end_date)
            ->first();
        if (sizeof($intersected) > 0) {
            Session::flash("flash_notification", [
                "level" => "danger",
                "message" => "Tidak dapat mengajukan cuti karena tanggal pengajuan "
                    . "sudah pernah diajukan sebelumnya (ID " . $intersected->id . ": " 
                    . $intersected->formattedPeriod . ")."
            ]);
            return false;            
        }
    }

    public function created(Absence $absence)
    {
        // karyawan yang membuat absence
        $employee = Auth::user()->employee()->first();

        // mendapatkan absence_type_id dari kuota cuti yang digunakan
        $absence_type_id = AbsenceQuota::activeAbsenceQuotaOf(
            Auth::user()
                ->personnel_no, $absence->start_date, $absence->end_date)
                ->first()
            ->absence_type_id;

        // mendapatkan flow_id untuk absences dari file config
        // mencari sequence pertama dari flow_id diatas
        // mengembalikan flowstage dan mengakses stage_id
        $flow_id = config('emss.flows.absences');
        $stage_id = FlowStage::firstSequence($flow_id)->first()->stage_id;

        // mengisi absence type dari server bukan dari request
        $absence->absence_type_id = $absence_type_id;

        // mengisi stage id melalui mekanisme flow stage
        $absence->stage_id = $stage_id;

        // simpan perubahan
        $absence->save();
        
        // mencari atasan dari karyawan yang mengajukan absences
        $closestBoss = $employee->closestBoss();

        // mencari direktur dari karyawan yang mengajukan absence
        $director = $employee->director();

        // buat record untuk absence approval
        $absence_approval = new AbsenceApproval();

        // foreign key pada absence approval
        $absence_approval->absence_id = $absence->id;

        // NEED TO IMPLEMENT FLOW STAGE
        // mengambil status dari firststatus
        $absence_approval->sequence = 1;
        $absence_approval->status_id = Status::firstStatus()->id;
                
        // JIKA karyawan tersebut mempunyai atasan langsung
        // maka simpan data atasan sebagai absence approval
        // JIKA TIDAK mempunyai atasan langsung
        // maka absence approval dibuat seolah-olah sudah disetujui 
        // contoh: karyawan yang atasannya langsung direktur
        // atau deputi (UTOMO NUGROHO) 
        if ($closestBoss) {
            // menyimpan personnel_no dari closest boss
            $absence_approval->regno = $closestBoss->personnel_no;

            // menyimpan data persetujuan
            $absence_approval->save();

        } else {
            // bypass regno menggunakan admin  dan sequence
            $admin = Role::retrieveAdmin();
            $absence_approval->regno = $admin->personnel_no;
            $absence_approval->sequence = 1;
            
            // menyimpan data persetujuan
            $absence_approval->save();

            // mengubah status menjadi approved
            $absence_approval->status_id = Status::ApproveStatus()->id;
            $absence_approval->text = 'Disetujui oleh Admin';

            // menyimpan perubahan agar mentrigger observer
            $absence_approval->save();
        }
    }
    
    public function updated(Absence $absence)
    {
        // apakah sudah selesai
        if ($absence->isFinished) {
            // mencari data kuota cuti yang dipakai
            $absenceQuota = AbsenceQuota::activeAbsenceQuota($absence->personnel_no)
                ->first();
            
            // menambah pemakaian cuti pada periode tersebut
            // seharusnya ada tabel history (many to many)
            // pemakaian cuti berkorelasi dengan absence quota
            $absenceQuota->deduction += $absence->deduction;

            // simpan data kuota cuti
            $absenceQuota->save();

            // to adalah karyawan yang mengajukan
            $to = $absence->user()->first();    

            // sistem mengirim email notifikasi
            $to->notify(new LeaveSentToSapMessage($absence));            
        }
    }
}
