<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Attendance
 * @package App\Models
 * @version July 11, 2018, 12:00 pm UTC
 *
 * @property \App\Models\AttendanceType attendanceType
 * @property \App\Models\Stage stage
 * @property \Illuminate\Database\Eloquent\Collection absenceApprovals
 * @property \Illuminate\Database\Eloquent\Collection absences
 * @property \Illuminate\Database\Eloquent\Collection AttendanceApproval
 * @property \Illuminate\Database\Eloquent\Collection attendanceQuotas
 * @property \Illuminate\Database\Eloquent\Collection permissionRole
 * @property \Illuminate\Database\Eloquent\Collection userRoles
 * @property integer personnel_no
 * @property date start_date
 * @property date end_date
 * @property integer attendance_type_id
 * @property integer stage_id
 */
class Attendance extends Model
{

    public $table = 'attendances';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'personnel_no',
        'start_date',
        'end_date',
        'attendance_type_id',
        'stage_id'
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
        'attendance_type_id' => 'integer',
        'stage_id' => 'integer'
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
    public function attendanceType()
    {
        return $this->belongsTo(\App\Models\AttendanceType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function stage()
    {
        return $this->belongsTo(\App\Models\Stage::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function attendanceApprovals()
    {
        return $this->hasMany(\App\Models\AttendanceApproval::class);
    }
}
