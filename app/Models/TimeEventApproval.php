<?php

namespace App\Models;

use Eloquent as Model;

class TimeEventApproval extends Model
{
    public $fillable = [
        'time_event_id',
        'regno',
        'sequence',
        'status_id',
        'text',
    ];
    
    protected $casts = [
        'id' => 'integer',
        'time_event_id' => 'integer',
        'regno' => 'integer',
        'sequence' => 'integer',
        'status_id' => 'integer',
        'text' => 'string'
    ];

    public static $rules = [

    ];

    public function attendance()
    {
        return $this->belongsTo('App\Models\TimeEvent');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }
}
