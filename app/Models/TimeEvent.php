<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Stage;
use App\Traits\ReceiveStage;

class TimeEvent extends Model
{
    use ReceiveStage;

    public $fillable = [
        'personnel_no',
        'check_date',
        'check_time',
        'time_event_type_id',
        'stage_id',
        'note',
    ];

    protected $casts = [
        'id' => 'integer',
        'personnel_no' => 'integer',
        'check_date' => 'date',
        'check_time' => 'time',
        'time_event_type_id' => 'integer',
        'stage_id' => 'integer',
        'note' => 'string'
    ];

    public static $rules = [
        
    ];

    public function user()
    {
        // many-to-one relationship dengan User
        return $this->belongsTo('App\User', 'personnel_no', 'personnel_no');
    }

    public function employee()
    {
        // many-to-one relationship dengan Employee
        return $this->belongsTo('App\Models\Employee', 'personnel_no', 'personnel_no');
    }     

    public function timeEventType()
    {
        // many-to-one relationship dengan TimeEventType
        return $this->belongsTo('App\Models\TimeEventType');
    }

    public function stage()
    {
        // many-to-one relationship dengan Stage
        return $this->belongsTo('App\Models\Stage');
    }

    public function timeEventApprovals()
    {
        // one-to-many relatioship dengan TimeEventApproval
        return $this->hasMany('App\Models\TimeEventApproval');
    }

    public function scopeMonthYearPeriodOf($query, $m, $y, $p)
    {
        return $query->whereMonth('check_date', $m)
            ->whereYear('check_date', $y)
            ->where('personnel_no', $p);
    }

    public function getPlainIdAttribute()
    {
        return 'time_event-' . $this->id;
    }

    public function getFormattedCheckDateAttribute()
    {
        return $this->check_date->format('d.m.Y');
    }
}