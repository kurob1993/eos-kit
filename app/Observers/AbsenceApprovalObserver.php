<?php

namespace App\Observers;

use App\Notifications\LeaveApprovalMessage;
use App\Models\AbsenceApproval;
use App\Models\Stage;
use App\Message;

class AbsenceApprovalObserver
{
    public function updated(AbsenceApproval $absenceApproval)
    {
      // $flow_id  = config('emss.flows.absences');
      // $flow_stage = FlowStage::nextSequence($flow_id);
      
      // mencari data absence sesuai dengan relatioship
      $absence = $absenceApproval->absence()->first();

      // from adalah dari atasan
      $from = $absenceApproval->user()->first();
      
      // to adalah karyawan yang mengajukan
      $to = $absence->user()->first();      

      // menyimpan catatan pengiriman pesan
      $message = new Message;
      
      // apakah data absence sudah disetujui
      if ($absenceApproval->isApproved) {
        
        // NEED TO IMPLEMENT FLOW STAGE (send to SAP)
        $absence->stage_id = Stage::sentToSapStage()->id;

        // message history
        $messageAttribute = sprintf('Leave approved from %s to %s',
        $from->personnelNoWithName, $to->personnelNoWithName);
      } else {

        // NEED TO IMPLEMENT FLOW STAGE (denied)
        $absence->stage_id = 5;

        // message history
        $messageAttribute = sprintf('Leave rejected from %s to %s',
        $from->personnelNoWithName, $to->personnelNoWithName);
      }
      
      // simpan data message history lainnya
      $message->setAttribute('from', $from->id);
      $message->setAttribute('to', $to->id);
      $message->setAttribute('message', $messageAttribute);
      
      // simpan message history
      $message->save();
      
      // update data absence
      $absence->save();

      // sistem mengirim email notifikasi dari atasan ke
      // karyawan yang mengajukan         
      $to->notify(new LeaveApprovalMessage($from, $absenceApproval));
    }
}