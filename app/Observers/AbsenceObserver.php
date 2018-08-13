<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Absence;
use App\Models\AbsenceQuota;
use App\Models\Stage;
use App\Models\FlowStage;
use App\Models\AbsenceApproval;
use App\Models\Status;
use App\User;
use Session;

class AbsenceObserver
{
  public function creating(Absence $absence)
  {
    // ambil kuota cuti berdasarkan tanggal mulai & berakhir cuti
    $absence_quota = AbsenceQuota::activeAbsenceQuotaOf(
      Auth::user()->personnel_no, $absence->start_date, $absence->end_date)
      ->first();

      // apakah tidak ada kuota cuti?
      if (empty($absence_quota)) {
        Session::flash("flash_notification", [
          "level"   =>  "danger",
          "message" =>  "Tidak dapat mengajukan cuti karena belum ada "
          . "kuota cuti untuk periode pengajuan ("
          . $absence->start_date . "-" . $absence->end_date . "). "
          . "Silahkan hubungi Divisi HCI&amp;A."
        ]);
        return false;
      }

      // apakah sisa cuti (balance)  kurang dari pengajuan (deduction)?
      if ($absence_quota->balance < $absence->deduction) {
        Session::flash("flash_notification", [
          "level"   =>  "danger",
          "message" =>  "Tidak dapat mengajukan cuti karena jumlah pengajuan cuti "
          . "melebihi sisa cuti periode saat ini "
          . "(Sisa Cuti =" . $absence_quota->balance . " < pengajuan cuti="
          . $absence->deduction . "). Silahkan ajukan cuti dengan jumlah "
          . "kurang dari/sama dengan sisa cuti."
        ]);
        return false;
      }
    }

    public function created(Absence $absence)
    {

      // mendapatkan absence_type_id dari kuota cuti yang digunakan
      $absence_type_id = AbsenceQuota::activeAbsenceQuotaOf(
        Auth::user()
          ->personnel_no, $absence->start_date, $absence->end_date)
          ->first()
          ->absence_type_id;

      // mendapatkan flow_id untuk absences dari file config
      // mencari sequence pertama dari flow_id diatas
      // mengembalikan flowstage dan mengakses stage_id
      $flow_id  = config('emss.flows.absences');
      $stage_id = FlowStage::firstSequence($flow_id)->first()->stage_id;

      $absence->absence_type_id = $absence_type_id;
      $absence->stage_id = $stage_id;
      $absence->save();

      $absence_approval = new AbsenceApproval();
      $absence_approval->absence_id = $absence->id;
      $absence_approval->regno = Auth::user()->employee()->first()->closestBoss()->personnel_no;
      $absence_approval->sequence = 1;
      $absence_approval->status_id = Status::firstStatus()->id;
      $absence_approval->save();



    }

    public function deleting(Absence $absence)
    {
      //
    }
  }
