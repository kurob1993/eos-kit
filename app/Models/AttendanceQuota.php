<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\FormatDates;
use App\Traits\PeriodDates;
use App\Traits\ReceiveStage;

class AttendanceQuota extends Model
{
    use FormatDates, PeriodDates, ReceiveStage;
    
    public $fillable = [
        'personnel_no',
        'start_date',
        'end_date',
        'attendance_quota_type_id',
        'overtime_reason_id',
        'from',
        'to'
    ];

    protected $casts = [
        'id' => 'integer',
        'personnel_no' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'attendance_quota_type_id' => 'integer',
        'overtime_reason_id' => 'integer'
    ];

    public static $rules = [
        
    ];
    
    public function user()
    {
        // many-to-one relationship dengan User
        return $this->belongsTo('App\User', 'personnel_no', 'personnel_no');
    }

    public function stage()
    {
        // many-to-one relationship dengan Stage
        return $this->belongsTo('App\Models\Stage')->withDefault();
    }

    public function attendaceQuotaApproval()
    {
        // one-to-many relationship dengan AttendanceQuotaApproval
        return $this->hasMany('App\Models\AttendanceQuotaApproval');
    }

    public function attendanceQuotaType()
    {
        // many-to-one relationship dengan AttendanceQuotaType
        return $this->belongsTo('App\Models\AttendanceQuotaType');
    }

    public function overtimeReason()
    {
        // many-to-one relationship dengan OvertimeReason
        return $this->belongsTo('App\Models\OvertimeReason');
    }
}
