<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Traits\PeriodDates;
use App\Traits\ReceiveStage;
use App\Traits\OfLoggedUser;

class AttendanceQuota extends Model
{
    use PeriodDates, ReceiveStage, OfLoggedUser;
    
    public $fillable = [
        'personnel_no',
        'start_date',
        'end_date',
        'attendance_quota_type_id',
        'overtime_reason_id',
        'from',
        'to',
        'secretary_id'
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

    public function employee()
    {
        // many-to-one relationship dengan Employee
        return $this->belongsTo('App\Models\Employee', 'personnel_no', 'personnel_no');
    }    

    public function stage()
    {
        // many-to-one relationship dengan Stage
        return $this->belongsTo('App\Models\Stage')->withDefault();
    }

    public function attendanceQuotaApproval()
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

    public function secretary()
    {
        return $this->belongsTo('App\Models\Secretary');
    }

    public function getFirstApprovalAttribute()
    {
        // mencari approval dengan sequence 1
        return $this->attendanceQuotaApproval()->where('sequence', 1)->first();
    }

    public function getSecondApprovalAttribute()
    {
        // mencari approval dengan sequence 2
        return $this->attendanceQuotaApproval()->where('sequence', 2)->first();
    }

    public function getPlainIdAttribute()
    {
        return 'overtime-' . $this->id;
    }

    public function getDurationAttribute()
    {
        $start = Carbon::createFromFormat('Y-m-d H:i:s', 
            $this->start_date->format('Y-m-d') . ' '. $this->from);
        $end = Carbon::createFromFormat('Y-m-d H:i:s', 
            $this->end_date->format('Y-m-d') . ' '. $this->to);
        
        return $end->diffInMinutes($start);
    }
}
