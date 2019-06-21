<?php

namespace App\Observers;

use Session;
use Illuminate\Support\Facades\Auth;
use App\Notifications\LeaveSentToSapMessage;
use App\Notifications\AbsenceDeletedMessage;
use App\Role;
use App\User;
use App\Models\Absence;
use App\Models\AbsenceApproval;
use App\Models\AbsenceQuota;
use App\Models\FlowStage;
use App\Models\Status;

class AbsenceObserver
{
    public function creating(Absence $absence)
    {
        // jika absence adalah cuti tahunan / cuti besar (leaves)
        if ($absence->isALeave) {
            // ambil kuota cuti berdasarkan tanggal mulai & berakhir cuti
            $absence_quota = AbsenceQuota::activeAbsenceQuotaOf(
                $absence->personnel_no, $absence->start_date, $absence->end_date)
                ->first();
            if(!$absence_quota){
                Session::flash("flash_notification", [
                    "level" => "danger",
                    "message" => "Tidak dapat mengajukan cuti karena".
                    "pengajuan cuti dilakukan pada dua periode yang berbeda"
                ]);
                return false;
            }
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
        }

        // apakah tanggal absence sudah pernah dilakukan sebelumnya (intersection)
        // HARUS DITAMBAHKAN APABILA dari masing-masing intersected statusnya DENIED
        // JIKA DENIED(5) dan CANCELLED(6) tidak termasuk intersected
        $intersected = Absence::where('personnel_no', $absence->personnel_no)
            ->whereNotIn('stage_id',[5,6])
            ->intersectWith($absence->start_date, $absence->end_date)
            ->first();
            
        if ( $intersected ) {
            Session::flash("flash_notification", [
                "level" => "danger",
                "message" => "Tidak dapat melakukan pengajuan pada tanggal tersebut "
                . "karena sudah pernah diajukan sebelumnya (ID " . $intersected->id . ": "
                . $intersected->formattedPeriod . ").",
            ]);
            return false;
        }
    }

    public function created(Absence $absence)
    {
        // karyawan yang membuat absence
        $employee = $absence->employee()->first();

        if ($absence->isALeave) {
            // mendapatkan absence_type_id dari kuota cuti yang digunakan
            $absence_type_id = AbsenceQuota::activeAbsenceQuotaOf(
                $absence->personnel_no, 
                $absence->start_date, 
                $absence->end_date
            )
            ->first()
            ->absence_type_id;
    
            // mengisi absence type dari server bukan dari request
            $absence->absence_type_id = $absence_type_id;
        }

        // mendapatkan flow_id untuk absences dari file config
        // mencari sequence pertama dari flow_id diatas
        // mengembalikan flowstage dan mengakses stage_id
        $flow_id = config('emss.flows.absences');
        $stage_id = FlowStage::firstSequence($flow_id)->first()->stage_id;
            
        // mengisi stage id melalui mekanisme flow stage
        $absence->stage_id = $stage_id;

        // simpan perubahan
        $absence->save();

        // mencari atasan dari karyawan yang mengajukan absences
        $closestBoss = $employee->minSuperintendentBoss();

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
        if ($employee->is_a_transfer_knowledge || !$closestBoss) {
            
            // bypass regno menggunakan admin dan sequence
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
        } else {
            // menyimpan personnel_no dari closest boss
            $absence_approval->regno = $closestBoss->personnel_no;

            // menyimpan data persetujuan
            $absence_approval->save();
        }
    }

    public function updating(Absence $absence)
    {
        // hanya yang stage sent to sap dan merupakan cuti
        if ($absence->is_waiting_approval && $absence->is_a_leave)
        {
            // mencari data kuota cuti yang dipakai
            $absenceQuota = AbsenceQuota::activeAbsenceQuota($absence->personnel_no)
                ->first();

            // number = 12 deduction existing = 10 
            // to be deducted = 4 TIDAK BISA
            // to be deducted = 2 -> to be deducted = 2 BISA
            // sum = deduction existing + to be deducted = 12
            // if (sum > number)
            // return false batalkan

            // menghitung total deduction yang akan ditambahkan
            $toBeDeducted = $absence->deduction + $absenceQuota->deduction;

            // apakah total deduction yang akan ditambahkan melebihi number
            if ($toBeDeducted > $absenceQuota->number)
            {
                Session::flash("flash_notification", [
                    "level" => "danger",
                    "message" => "Tidak dapat mengkonfirmasi data di SAP karena "
                    . "total durasi pengajuan > total kuota cuti (Total kuota cuti :" . $absenceQuota->number . " "
                    . "Total durasi pengajuan :" . $toBeDeducted . ").",
                ]);
                return false;
            }
        }
    }

    public function updated(Absence $absence)
    {
        // apakah sudah selesai
        if ($absence->isSuccess) {

            /** bagian  ini di komen di karenakan penambahan 
             *  dan pengurangan kuota cuti diprosess mealalui XI SAP
             *  Dari sini
            */

            // jika absence adalah cuti maka lakukan penambahan deduction
            // if ($absence->is_a_leave) {
                // // mencari data kuota cuti yang dipakai
                // $absenceQuota = AbsenceQuota::activeAbsenceQuota($absence->personnel_no)
                //     ->first();
    
                // // menambah pemakaian cuti pada periode tersebut
                // // seharusnya ada tabel history (many to many)
                // // pemakaian cuti berkorelasi dengan absence quota
                // $absenceQuota->deduction += $absence->deduction;
    
                // // simpan data kuota cuti
                // $absenceQuota->save();
                
            // }

            /** Sampai sini */

            // to adalah karyawan yang mengajukan
            $to = $absence->user()->first();

            // sistem mengirim email notifikasi
            $to->notify(new LeaveSentToSapMessage($absence));
        }
    }

    public function deleting(Absence $absence)
    {
        $approvals = $absence->absenceApprovals;
        
        // hapus semua approval terkait absence
        foreach ($approvals as $approval)
            $approval->delete();

        // // sistem mengirim notifikasi
        $to = $absence->user;
        $to->notify(new AbsenceDeletedMessage($absence));    
    }

}
