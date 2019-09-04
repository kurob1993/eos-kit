<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ReceiveStatus;
use App\Traits\ParentStage;
use App\Traits\OfLoggedUserApproval;

class TravelApproval extends Model
{
    use ReceiveStatus, ParentStage, OfLoggedUserApproval;

    public function travel()
    {
        // many-to-one relationship dengan Employee
        return $this->belongsTo('App\Models\Travel');
    } 
    
    public function employee()
    {
        // many-to-one relationship dengan Employee
        return $this->belongsTo('App\Models\Employee', 'regno', 'personnel_no');
    } 
}
