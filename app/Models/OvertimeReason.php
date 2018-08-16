<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class OvertimeReason
 * @package App\Models
 * @version July 11, 2018, 12:05 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection absenceApprovals
 * @property \Illuminate\Database\Eloquent\Collection absences
 * @property \Illuminate\Database\Eloquent\Collection attendanceApprovals
 * @property \Illuminate\Database\Eloquent\Collection AttendanceQuota
 * @property \Illuminate\Database\Eloquent\Collection attendances
 * @property \Illuminate\Database\Eloquent\Collection permissionRole
 * @property \Illuminate\Database\Eloquent\Collection userRoles
 * @property string overtimeid
 * @property string text
 */
class OvertimeReason extends Model
{

    public $table = 'overtime_reasons';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'overtimeid',
        'text'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'overtimeid' => 'string',
        'text' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function attendanceQuotas()
    {
        return $this->hasMany(\App\Models\AttendanceQuota::class);
    }
}
