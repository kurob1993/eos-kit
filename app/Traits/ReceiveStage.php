<?php

namespace App\Traits;

use App\Models\Stage;

trait ReceiveStage
{
    public function getIsSuccessAttribute()
    {
        // apakah absence ini tahapnya sudah success
        return ($this->stage_id == Stage::successStage()->id) ?
            true : false;
    }

    public function getIsSentToSapAttribute()
    {
        // apakah absence ini tahapnya sent to SAP
        return ($this->stage_id == Stage::sentToSapStage()->id) ?
            true : false;
    }

    public function getIsFailedAttribute()
    {
        // apakah absence ini tahapnya failed
        return ($this->stage_id == Stage::failedStage()->id) ?
            true : false;
    }    

    public function getIsDeniedAttribute()
    {
        // apakah absence ini tahapnya denied
        return ($this->stage_id == Stage::deniedStage()->id) ?
            true : false;
    }

    public function getIsCancelledAttribute()
    {
        // apakah absence ini tahapnya cancelled
        return ($this->stage_id == Stage::cancelledStage()->id) ?
            true : false;
    }
    
    public function getIsWaitingApprovalAttribute()
    {
        // apakah tahapnya waiting approval
        return ($this->stage_id == Stage::waitingApprovalStage()->id) ?
            true : false;
    }

    public function scopeIncompleted($query)
    {
        // apakah sudah selesai? (finished, failed, denied)
        return $query->whereIn('stage_id', [
            Stage::waitingApprovalStage()->id,
            Stage::sentToSapStage()->id ]);
    }

    public function scopeSentToSapOnly($query)
    {
        // mencari yang sent to sap
        return $query->where('stage_id', Stage::sentToSapStage()->id);
    }
    
    public function scopeWaitingApprovalOnly($query)
    {
        // mencari yang sent to sap
        return $query->where('stage_id', Stage::waitingApprovalStage()->id);
    }

    public function scopeSuccessOnly($query)
    {
        // mencari yang sent to sap
        return $query->where('stage_id', Stage::successStage()->id);
    }

    public function scopeDeniedOnly($query)
    {
        // mencari yang sent to sap
        return $query->where('stage_id', Stage::deniedStage()->id);
    }

    public function scopeFailedOnly($query)
    {
        // mencari yang sent to sap
        return $query->where('stage_id', Stage::failedStage()->id);
    }
    
    public function scopeCancelledOnly($query)
    {
        // mencari yang sent to sap
        return $query->where('stage_id', Stage::cancelledStage()->id);
    }

}