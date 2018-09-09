<?php

namespace App\Observers;

use App\Message;
use App\Models\TimeEventApproval;
use App\Models\Stage;
use App\Notifications\TimeEventApprovalMessage;

class TimeEventApprovalObserver
{
    public function updated(TimeEventApproval $timeEventApproval)
    {
      // $flow_id  = config('emss.flows.time_events');
      // $flow_stage = FlowStage::nextSequence($flow_id);
      
      // mencari data timeEvent sesuai dengan relatioship
      $timeEvent = $timeEventApproval->timeEvent()->first();

      // from adalah dari atasan
      $from = $timeEventApproval->user()->first();
      
      // to adalah karyawan yang mengajukan
      $to = $timeEvent->user()->first();      

      // menyimpan catatan pengiriman pesan
      $message = new Message;
      
      // apakah data timeEvent sudah disetujui
      if ($timeEventApproval->isApproved) {
        
        // NEED TO IMPLEMENT FLOW STAGE (send to SAP)
        $timeEvent->stage_id = 2;

        // message history
        $messageAttribute = sprintf('Time Event approved from %s to %s',
        $from->personnelNoWithName, $to->personnelNoWithName);
      } else {

        // NEED TO IMPLEMENT FLOW STAGE (denied)
        $timeEvent->stage_id = 5;

        // message history
        $messageAttribute = sprintf('Time Event rejected from %s to %s',
        $from->personnelNoWithName, $to->personnelNoWithName);
      }
      
      // simpan data message history lainnya
      $message->setAttribute('from', $from->id);
      $message->setAttribute('to', $to->id);
      $message->setAttribute('message', $messageAttribute);
      
      // simpan message history
      $message->save();
      
      // update data timeEvent
      $timeEvent->save();

      // sistem mengirim email notifikasi dari atasan ke
      // karyawan yang mengajukan         
      $to->notify(new TimeEventApprovalMessage($from, $timeEventApproval));
    }
}