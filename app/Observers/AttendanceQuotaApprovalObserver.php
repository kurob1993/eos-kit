<?php

namespace App\Observers;

use App\Message;
use App\Models\AttendanceQuotaApproval;
use App\Models\Stage;
use App\Notifications\OvertimeApprovalMessage;

class AttendanceQuotaApprovalObserver
{
    public function updating(AttendanceQuotaApproval $currentAqa)
    {
      // $flow_id  = config('emss.flows.attendance_quotas');
      // $flow_stage = FlowStage::nextSequence($flow_id);
      
      // mencari semua AttendanceQuota dari approval ini
      $aq = $currentAqa->attendanceQuota;

      // to adalah karyawan yang mengajukan
      // from adalah dari atasan
      // menyimpan catatan pengiriman pesan
      $to = $aq->user;
      $from = $currentAqa->user;    
      $message = new Message;

      switch ($currentAqa->sequence) {
        case 1: 
          // message history
          $messageAttribute = sprintf('Overtime partially approved from %s to %s',
          $from->personnelNoWithName, $to->personnelNoWithName);
        break;
        case 2: 
          // mencari approval pertama
          $firstApproval = $aq->first_approval;

          // apakah approval pertama sudah disetujui pada sequence sebelumnya
          // jika ya proses ke Send to SAP, jika tidak batalkan persetujuan
          if ($firstApproval->is_approved) {
            // NEED TO IMPLEMENT FLOW STAGE (send to SAP)
            $aq->stage_id = Stage::sentToSapStage()->id;

            // message history
            $messageAttribute = sprintf('Overtime completely approved from %s to %s',
            $from->personnelNoWithName, $to->personnelNoWithName); 

            // simpan perubahan Stage untuk AttendanceQuota
            $aq->save();
          } else {
            // tampilkan pesan bahwa persetujuan sebelumnya harus diselesaikan
            Session::flash("flash_notification", [
              "level"   =>  "danger",
              "message"=>"Tidak dapat melakukan persetujuan karena data persetujuan " 
              . "pada proses sebelumnya belum diselesaikan." 
              . $firstApproval->user->personnelNoWithName
            ]);
            // batalkan persetujuan
            return false;
          }
        break;
      }

      // simpan data message history lainnya
      $message->setAttribute('from', $from->id);
      $message->setAttribute('to', $to->id);
      $message->setAttribute('message', $messageAttribute);
      
      // simpan message history
      $message->save();
      
      // sistem mengirim email notifikasi dari atasan ke
      // karyawan yang mengajukan         
      $to->notify(new OvertimeApprovalMessage($from, $currentAqa));
    }
}