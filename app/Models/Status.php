<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class Status
 * @package App\Models
 * @version July 11, 2018, 12:05 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection AbsenceApproval
 * @property \Illuminate\Database\Eloquent\Collection absences
 * @property \Illuminate\Database\Eloquent\Collection AttendanceApproval
 * @property \Illuminate\Database\Eloquent\Collection attendanceQuotas
 * @property \Illuminate\Database\Eloquent\Collection attendances
 * @property \Illuminate\Database\Eloquent\Collection permissionRole
 * @property \Illuminate\Database\Eloquent\Collection userRoles
 * @property string description
 */
class Status extends Model
{

    public $table = 'status';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'description'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'description' => 'string'
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
    public function absenceApprovals()
    {
        return $this->hasMany(\App\Models\AbsenceApproval::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function attendanceApprovals()
    {
        return $this->hasMany(\App\Models\AttendanceApproval::class);
    }
}
