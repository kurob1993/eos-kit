<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class AttendanceType
 * @package App\Models
 * @version July 11, 2018, 12:00 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection absenceApprovals
 * @property \Illuminate\Database\Eloquent\Collection absences
 * @property \Illuminate\Database\Eloquent\Collection attendanceApprovals
 * @property \Illuminate\Database\Eloquent\Collection attendanceQuotas
 * @property \Illuminate\Database\Eloquent\Collection Attendance
 * @property \Illuminate\Database\Eloquent\Collection permissionRole
 * @property \Illuminate\Database\Eloquent\Collection userRoles
 * @property string subtype
 * @property string text
 */
class AttendanceType extends Model
{

    public $table = 'attendance_types';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'subtype',
        'text'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'subtype' => 'string',
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
    public function attendances()
    {
        return $this->hasMany(\App\Models\Attendance::class);
    }
}
