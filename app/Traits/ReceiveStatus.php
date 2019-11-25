<?php

namespace App\Traits;

use App\Models\Status;

trait ReceiveStatus
{
    public function getIsNotWaitingAttribute()
    {
        // apakah approval sudah disetujui ATAU ditolak
        // TRUE apabila sudah setuju ATAU sudah tolak
        // FALSE apabila masih waiting
        return ($this->status_id <> Status::firstStatus()->id) ?
            true : false;
    }

    public function getIsWaitingAttribute()
    {
        // apakah approval masih waiting
        return ($this->status_id == Status::firstStatus()->id) ?
            true : false;
    }
    
    public function getIsApprovedAttribute()
    {
        // apakah approval sudah disetujui
        return ($this->status_id == Status::approveStatus()->id) ?
            true : false;
    }

    public function getIsRejectedAttribute()
    {
        // apakah approval sudah ditolak
        return ($this->status_id == Status::rejectStatus()->id) ?
            true : false;
    }

    public function scopewaitedForApproval($query)
    {
        return $query->where('status_id', Status::firstStatus()->id);
    }
}