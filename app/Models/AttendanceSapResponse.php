<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSapResponse extends Model
{
    public function attendance()
    {
        return $this->belongsTo('App\Models\Attendace','reqno');
    }
}
