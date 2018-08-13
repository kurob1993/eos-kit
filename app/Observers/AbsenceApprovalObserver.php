<?php

namespace App\Observers;

use App\Models\AbsenceApproval;

class AbsenceApprovalObserver
{
    public function creating(AbsenceApproval $absenceApproval)
    {

    }

    public function created(AbsenceApproval $absenceApproval)
    {

    }

    public function updated(AbsenceApproval $absenceApproval)
    {
      // $flow_id  = config('emss.flows.absences');
      // $flow_stage = FlowStage::nextSequence($flow_id);

      $absence = $absenceApproval->absence()->first();
      // NEED TO IMPLEMENT FLOW STAGE
      $absence->stage_id = 2;
      $absence->save();

    }
}
