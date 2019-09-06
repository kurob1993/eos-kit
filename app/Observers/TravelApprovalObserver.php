<?php

namespace App\Observers;

use Session;
// use App\Notifications\LeaveApprovalMessage;
// use App\Notifications\AbsenceApprovalCreatedMessage;
use App\Models\TravelApproval;
use App\Models\Stage;
use App\Models\Employee;
use App\Message;

class TravelApprovalObserver
{
    public function created(TravelApproval $TravelApproval)
    {
        // // to adalah karyawan yang mengajukan
        // $to = Employee::findByPersonnel($absenceApproval->regno)
        //     ->first()
        //     ->user;

        // // sistem mengirim email notifikasi
        // $to->notify(new AbsenceApprovalCreatedMessage($absenceApproval));
    }

    public function updated(TravelApproval $TravelApproval)
    {
      // $flow_id  = config('emss.flows.absences');
      // $flow_stage = FlowStage::nextSequence($flow_id);
      
      // mencari data absence sesuai dengan relatioship
      $travel = $TravelApproval->travel()->first();

      // from adalah dari atasan
      $from = $TravelApproval->user()->first();
      
      // to adalah karyawan yang mengajukan
      $to = Employee::findByPersonnel($travel->personnel_no)
        ->first()
        ->user;

      // menyimpan catatan pengiriman pesan
      $message = new Message;
      
      // apakah data absence sudah disetujui
      if ($TravelApproval->isApproved) {
        
        // NEED TO IMPLEMENT FLOW STAGE (send to SAP)
        $travel->stage_id = Stage::sentToSapStage()->id;

        // message history
        // $messageAttribute = sprintf('Leave approved from %s to %s',
        // $from->personnelNoWithName, $to->personnelNoWithName);
      } else {

        // NEED TO IMPLEMENT FLOW STAGE (denied)
        // $absence->stage_id = 5;

        // // message history
        // $messageAttribute = sprintf('Leave rejected from %s to %s',
        // $from->personnelNoWithName, $to->personnelNoWithName);
      }
      
      // // simpan data message history lainnya
      // $message->setAttribute('from', $from->id);
      // $message->setAttribute('to', $to->id);
      // $message->setAttribute('message', $messageAttribute);
      
      // // simpan message history
      // $message->save();
      
      // // update data absence
      $travel->save();

      // // sistem mengirim email notifikasi dari atasan ke
      // // karyawan yang mengajukan         
      // $to->notify(new LeaveApprovalMessage($from, $TravelApproval));
    }
}