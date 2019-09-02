<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PeriodDates;

class Travel extends Model
{
    use PeriodDates;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    
    public function getPlainIdAttribute()
    {
        return 'travel-' . $this->id;
    }
    public function employee()
    {
        // many-to-one relationship dengan Employee
        return $this->belongsTo('App\Models\Employee', 'personnel_no', 'personnel_no');
    } 
}
