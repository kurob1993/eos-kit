<?php

namespace App\Traits;

use App\Models\Stage;

trait ParentStage
{
    public function scopeWhereStageId($query, $r, $s)
    {
        // Querying Relationship Existence
        return $query->whereHas($r, function ($query) use ($s){
            $query->where('stage_id', $s);
        });
    }

    public function scopeWhereStageIsWaitingApproval($query, $r)
    {
        // Querying Relationship Existence
        return $query->whereHas($r, function ($query) {
            $query->waitingApprovalOnly();
        });        
    }
}
