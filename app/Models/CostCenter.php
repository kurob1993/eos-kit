<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee','boss','personnel_no');
    }
}
