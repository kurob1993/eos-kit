<?php

namespace App\Traits;

use App\Models\Status;

trait ReceiveStatus
{
    public function getIsNotWaitingAttribute()
    {
        // apakah absence approval sudah disetujui ATAU ditolak
        // TRUE apabila sudah setuju ATAU sudah tolak
        // FALSE apabila masih waiting
        return ($this->status_id <> Status::firstStatus()->id) ?
            true : false;
    }
    
    public function getIsApprovedAttribute()
    {
        // apakah absence approval sudah disetujui
        return ($this->status_id == Status::approveStatus()->id) ?
            true : false;
    }

    public function scopeWaitedForApproval($query)
    {
        return $query->where('status_id', Status::firstStatus()->id);
    }    
}