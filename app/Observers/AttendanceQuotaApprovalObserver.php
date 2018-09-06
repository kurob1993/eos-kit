<?php

namespace App\Observers;

use App\Notifications\PermitApprovalMessage;
use App\Models\AttendanceQuotaApproval;
use App\Message;

class AttendanceQuotaApprovalObserver
{
    public function updated(AttendanceQuotaApproval $attendanceQuotaApproval)
    {
      // $flow_id  = config('emss.flows.attendance_quotas');
      // $flow_stage = FlowStage::nextSequence($flow_id);
      
      // to adalah karyawan yang mengajukan
      $to = $attendanceQuotaApproval->attendanceQuota->user;

      // from adalah dari atasan
      $from = $attendanceQuotaApproval->user;    

      // menyimpan catatan pengiriman pesan
      $message = new Message;

      // attendanceQuota dari approval ini
      $aq = $attendanceQuotaApproval->attendanceQuota;

      $aqas = $aq->attendanceQuotaApproval;

      $allApproved = true;
      $allApproved &= $aqas->map(function ($a){
        return $a->isApproved;
      });
      
      // apakah data attendanceQuota sudah disetujui
      if ($allApproved) {
        
        // NEED TO IMPLEMENT FLOW STAGE (send to SAP)
        $attendanceQuota->stage_id = Stage::sentToSapStage()->id;

        // message history
        $messageAttribute = sprintf('Permit approved from %s to %s',
        $from->personnelNoWithName, $to->personnelNoWithName);
      } else {

        // NEED TO IMPLEMENT FLOW STAGE (denied)
        $attendanceQuota->stage_id = Stage::deniedStage()->id;

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
      $to->notify(new PermitApprovalMessage($from, $attendanceQuotaApproval));
    }
}