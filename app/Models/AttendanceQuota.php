<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class AttendanceQuota
 * @package App\Models
 * @version July 11, 2018, 12:05 pm UTC
 *
 * @property \App\Models\AttendanceQuotaType attendanceQuotaType
 * @property \App\Models\OvertimeReason overtimeReason
 * @property \Illuminate\Database\Eloquent\Collection absenceApprovals
 * @property \Illuminate\Database\Eloquent\Collection absences
 * @property \Illuminate\Database\Eloquent\Collection attendanceApprovals
 * @property \Illuminate\Database\Eloquent\Collection attendances
 * @property \Illuminate\Database\Eloquent\Collection permissionRole
 * @property \Illuminate\Database\Eloquent\Collection userRoles
 * @property integer personnel_no
 * @property date start_date
 * @property date end_date
 * @property integer attendance_quota_type_id
 * @property integer overtime_reason_id
 * @property time from
 * @property time to
 */
class AttendanceQuota extends Model
{

    public $table = 'attendance_quotas';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'personnel_no',
        'start_date',
        'end_date',
        'attendance_quota_type_id',
        'overtime_reason_id',
        'from',
        'to'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'personnel_no' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'attendance_quota_type_id' => 'integer',
        'overtime_reason_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function attendanceQuotaType()
    {
        return $this->belongsTo(\App\Models\AttendanceQuotaType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function overtimeReason()
    {
        return $this->belongsTo(\App\Models\OvertimeReason::class);
    }
}
