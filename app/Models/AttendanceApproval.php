<?php

namespace App\Models;

use Eloquent as Model;
use App\Traits\ReceiveStatus;
use App\Traits\ParentStage;

class AttendanceApproval extends Model
{
    use ReceiveStatus;
    use ParentStage;

    public $fillable = [
        'attendance_id',
        'regno',
        'sequence',
        'status_id',
        'text'
    ];

    protected $casts = [
        'id' => 'integer',
        'attendance_id' => 'integer',
        'regno' => 'integer',
        'sequence' => 'integer',
        'status_id' => 'integer',
        'text' => 'string'
    ];

    public static $rules = [

    ];

    public function user()
    {
        // many-to-one relationship dengan User
        return $this->belongsTo('App\User', 'regno', 'personnel_no');
    }

    public function employee()
    {
        // many-to-one relationship dengan Employee
        return $this->belongsTo('App\Models\Employee', 'regno', 'personnel_no');
    }

    public function attendance()
    {
        // many-to-one relationship dengan attendance
        return $this->belongsTo('\App\Models\Attendance');
    }

    public function permit()
    {
        // many-to-one relationship dengan absence
        return $this->attendance();
    }

    public function status()
    {
        // one-to-one relationship dengan status
        return $this->belongsTo('\App\Models\Status');
    }
}