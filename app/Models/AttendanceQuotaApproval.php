<?php

namespace App\Models;

use Eloquent as Model;
use App\Traits\ReceiveStatus;
use App\Traits\ParentStage;
use App\Traits\OfLoggedUserApproval;
use App\Traits\FormatDates;

class AttendanceQuotaApproval extends Model
{
    use ReceiveStatus, ParentStage, OfLoggedUserApproval, FormatDates;

    public $fillable = [ 'attendance_quota_id', 'regno', 'sequence', 'status_id', 'text' ];

    protected $casts = [
        'id' => 'integer',
        'attendance_quota_id' => 'integer',
        'regno' => 'integer',
        'sequence' => 'integer',
        'status_id' => 'integer',
        'text' => 'string'
    ];

    public static $rules = [ ];

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

    public function attendanceQuota()
    {
        // many-to-one relationship dengan attendanceQuota
        return $this->belongsTo('\App\Models\AttendanceQuota');
    }

    public function status()
    {
        // one-to-one relationship dengan status
        return $this->belongsTo('\App\Models\Status');
    }
}