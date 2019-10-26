<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkiApproval extends Model
{
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
}
