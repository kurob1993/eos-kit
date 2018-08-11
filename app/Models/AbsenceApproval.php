<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class AbsenceApproval
 * @package App\Models
 * @version July 11, 2018, 11:57 am UTC
 *
 * @property \App\Models\Absence absence
 * @property \App\Models\Status status
 * @property \Illuminate\Database\Eloquent\Collection absences
 * @property \Illuminate\Database\Eloquent\Collection attendanceApprovals
 * @property \Illuminate\Database\Eloquent\Collection attendanceQuotas
 * @property \Illuminate\Database\Eloquent\Collection attendances
 * @property \Illuminate\Database\Eloquent\Collection permissionRole
 * @property \Illuminate\Database\Eloquent\Collection userRoles
 * @property integer absence_id
 * @property integer regno
 * @property integer sequence
 * @property integer status_id
 * @property date approved
 * @property string text
 */
class AbsenceApproval extends Model
{

    public $table = 'absence_approvals';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'absence_id',
        'regno',
        'sequence',
        'status_id',
        'approved',
        'text'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'absence_id' => 'integer',
        'regno' => 'integer',
        'sequence' => 'integer',
        'status_id' => 'integer',
        'approved' => 'date',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function absence()
    {
        return $this->belongsTo(\App\Models\Absence::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function status()
    {
        return $this->belongsTo(\App\Models\Status::class);
    }
}
