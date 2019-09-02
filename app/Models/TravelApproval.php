<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ReceiveStatus;

class TravelApproval extends Model
{
    use ReceiveStatus;

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
