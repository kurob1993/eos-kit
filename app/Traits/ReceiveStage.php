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
        return ($this->stage_id == Stage::FailedStage()->id) ?
            true : false;
    }    

    public function getIsDeniedAttribute()
    {
        // apakah absence ini tahapnya failed
        return ($this->stage_id == Stage::FailedStage()->id) ?
            true : false;
    } 

    public function scopeIncompleted($query)
    {
        // apakah sudah selesai? (finished, failed, denied)
        return $query->whereIn('stage_id', [
            Stage::waitingApprovalStage()->id,
            Stage::sentToSapStage()->id ]);
    }    
}