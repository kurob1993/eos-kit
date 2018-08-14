<?php

namespace App\Observers;

use App\Models\AbsenceApproval;

class AbsenceApprovalObserver
{
    public function updated(AbsenceApproval $absenceApproval)
    {
      // $flow_id  = config('emss.flows.absences');
      // $flow_stage = FlowStage::nextSequence($flow_id);
      
      // mencari data absence sesuai dengan relatioship
      $absence = $absenceApproval->absence()->first();
      
      // apakah data absence sudah disetujui
      if ($absenceApproval->isApproved) {
        
        // NEED TO IMPLEMENT FLOW STAGE (send to SAP)
        $absence->stage_id = 2;
      } else {

        // NEED TO IMPLEMENT FLOW STAGE (denied)
        $absence->stage_id = 5;
      }

      // update data absence
      $absence->save();

    }
}