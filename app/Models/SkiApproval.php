<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ReceiveStatus;
use App\Traits\ParentStage;
use App\Traits\OfLoggedUserApproval;
use App\Traits\FormatDates;

class SkiApproval extends Model
{
    use ReceiveStatus, ParentStage, OfLoggedUserApproval, FormatDates;

    public function user()
    {
        // many-to-one relationship dengan User
        return $this->belongsTo('App\User', 'regno', 'personnel_no');
    }
    
    public function ski()
    {
        // many-to-one relationship dengan ski
        return $this->belongsTo('\App\Models\Ski');
    }

    public function employee()
    {
        // many-to-one relationship dengan Employee
        return $this->belongsTo('App\Models\Employee', 'regno', 'personnel_no');
    }

    public function status()
    {
        // one-to-one relationship dengan status
        return $this->belongsTo('\App\Models\Status');
    }
}
