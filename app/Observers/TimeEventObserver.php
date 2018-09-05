<?php

namespace App\Observers;

use Session;
use Illuminate\Support\Facades\Auth;
use App\Models\TimeEvent;
use App\Models\TimeEventApproval;
use App\Models\TimeEventQuota;
use App\Models\FlowStage;
use App\Models\Status;
use App\Notifications\TimeEventSentToSapMessage;
use App\Role;
use App\User;

class TimeEventObserver
{
    public function creating(TimeEvent $timeEvent)
    {
        // apakah tanggal tidak slash sudah pernah dilakukan sebelumnya (intersection)
        // HARUS DITAMBAHKAN APABILA dari masing-masing intersected statusnya DENIED
        // JIKA DENIED tidak termasuk intersected
        $intersected = TimeEvent::where('personnel_no', Auth::user()->personnel_no)
            ->where('check_date', $timeEvent->check_date)
            ->first();
        if (sizeof($intersected) > 0) {
            Session::flash("flash_notification", [
                "level" => "danger",
                "message" => "Tidak dapat mengajukan tidak slash karena tanggal pengajuan "
                . "sudah pernah diajukan sebelumnya (ID " . $intersected->id . ": "
                . $intersected->check_date . ").",
            ]);
            return false;
        }
    }

    public function created(TimeEvent $timeEvent)
    {
        // karyawan yang membuat time_event
        $employee = Auth::user()->employee()->first();

        // mendapatkan flow_id untuk time_events dari file config
        // mencari sequence pertama dari flow_id diatas
        // mengembalikan flowstage dan mengakses stage_id
        $flow_id = config('emss.flows.time_events');
        $stage_id = FlowStage::firstSequence($flow_id)->first()->stage_id;

        // mengisi stage id melalui mekanisme flow stage
        $timeEvent->stage_id = $stage_id;

        // simpan perubahan
        $timeEvent->save();

        // mencari atasan dari karyawan yang mengajukan time_events
        $closestBoss = $employee->superintendentBoss();

        // mencari direktur dari karyawan yang mengajukan time_event
        $director = $employee->director();

        // buat record untuk time_event approval
        $timeEventApproval = new TimeEventApproval();

        // foreign key pada time_event approval
        $timeEventApproval->time_event_id = $timeEvent->id;

        // NEED TO IMPLEMENT FLOW STAGE
        // mengambil status dari firststatus
        $timeEventApproval->sequence = 1;
        $timeEventApproval->status_id = Status::firstStatus()->id;

        // JIKA karyawan tersebut mempunyai atasan langsung
        // maka simpan data atasan sebagai time_event approval
        // JIKA TIDAK mempunyai atasan langsung
        // maka time_event approval dibuat seolah-olah sudah disetujui
        // contoh: karyawan yang atasannya langsung direktur
        // atau deputi (UTOMO NUGROHO)
        if ($closestBoss) {
            // menyimpan personnel_no dari closest boss
            $timeEventApproval->regno = $closestBoss->personnel_no;

            // menyimpan data persetujuan
            $timeEventApproval->save();

        } else {
            // bypass regno menggunakan admin  dan sequence
            $admin = Role::retrieveAdmin();
            $timeEventApproval->regno = $admin->personnel_no;
            $timeEventApproval->sequence = 1;

            // menyimpan data persetujuan
            $timeEventApproval->save();

            // mengubah status menjadi approved
            $timeEventApproval->status_id = Status::ApproveStatus()->id;
            $timeEventApproval->text = 'Disetujui oleh Admin';

            // menyimpan perubahan agar mentrigger observer
            $timeEventApproval->save();
        }
    }

    public function updated(TimeEvent $timeEvent)
    {
        // apakah sudah selesai
        if ($timeEvent->isSuccess) {
            // to adalah karyawan yang mengajukan
            $to = $timeEvent->user()->first();

            // sistem mengirim email notifikasi
            $to->notify(new TimeEventSentToSapMessage($timeEvent));
        }
    }
}
