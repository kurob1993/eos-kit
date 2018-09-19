<?php

namespace App\Observers;

use App\Notifications\PermitApprovalMessage;
use App\Models\AttendanceApproval;
use App\Message;

class AttendanceApprovalObserver
{
    public function updated(AttendanceApproval $attendanceApproval)
    {
      // $flow_id  = config('emss.flows.attendances');
      // $flow_stage = FlowStage::nextSequence($flow_id);
      
      // mencari data attendance sesuai dengan relatioship
      $attendance = $attendanceApproval->attendance()->first();

      // from adalah dari atasan
      $from = $attendanceApproval->user()->first();
      
      // to adalah karyawan yang mengajukan
      $to = $attendance->user()->first();      

      // menyimpan catatan pengiriman pesan
      $message = new Message;
      
      // apakah data attendance sudah disetujui
      if ($attendanceApproval->isApproved) {
        
        // NEED TO IMPLEMENT FLOW STAGE (send to SAP)
        $attendance->stage_id = 2;

        // message history
        $messageAttribute = sprintf('Permit approved from %s to %s',
        $from->personnelNoWithName, $to->personnelNoWithName);
      } else {

        // NEED TO IMPLEMENT FLOW STAGE (denied)
        $attendance->stage_id = 5;

        // message history
        $messageAttribute = sprintf('Permit rejected from %s to %s',
        $from->personnelNoWithName, $to->personnelNoWithName);
      }
      
      // simpan data message history lainnya
      $message->setAttribute('from', $from->id);
      $message->setAttribute('to', $to->id);
      $message->setAttribute('message', $messageAttribute);
      
      // simpan message history
      $message->save();
      
      // update data attendance
      $attendance->save();

      // sistem mengirim email notifikasi dari atasan ke
      // karyawan yang mengajukan         
      $to->notify(new PermitApprovalMessage($from, $attendanceApproval));
    }
}