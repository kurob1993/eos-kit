<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PeriodDates;
use App\Traits\ReceiveStage;

class Travel extends Model
{
    use PeriodDates, ReceiveStage;

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

    public function travelApproval()
    {
        // many-to-one relationship dengan Employee
        return $this->hasMany('App\Models\TravelApproval');
    } 

    public function stage()
    {
        // many-to-one relationship dengan Stage
        return $this->belongsTo('App\Models\Stage')->withDefault();
    }
}
