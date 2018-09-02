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

    public function attendanceQuotaType()
    {
        return $this->belongsTo('App\Models\AttendanceQuotaType');
    }

    public function overtimeReason()
    {
        return $this->belongsTo('App\Models\OvertimeReason');
    }
}
