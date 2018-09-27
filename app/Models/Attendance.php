<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Stage;
use App\Traits\PeriodDates;
use App\Traits\ReceiveStage;


class Attendance extends Model
{
    use PeriodDates, ReceiveStage;

    public $fillable = [
        'personnel_no',
        'start_date',
        'end_date',
        'attendance_type_id',
        'stage_id',
        'note',
        'attachment',
    ];

    protected $casts = [
        'id' => 'integer',
        'personnel_no' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'attendance_type_id' => 'integer',
        'stage_id' => 'integer',
    ];

    public static $rules = [
        
    ];

    public function user()
    {
        // many-to-one relationship dengan User
        return $this->belongsTo('App\User', 'personnel_no', 'personnel_no');
    }

    public function attendanceType()
    {
        // many-to-one relationship dengan AttendanceType
        return $this->belongsTo('App\Models\AttendanceType');
    }

    public function stage()
    {
        // many-to-one relationship dengan Stage
        return $this->belongsTo('App\Models\Stage');
    }

    public function attendanceApprovals()
    {
        // one-to-many relatioship dengan AttendanceApproval
        return $this->hasMany('App\Models\AttendanceApproval');
    }

    public function permitType()
    {
        // column aliasing untuk attendanceType
        return $this->attendanceType();
    }

    public function permitApprovals()
    {
        // column aliasing untuk attendanceApprovals
        return $this->attendanceApprovals();
    }

    public function scopeIncompleted($query)
    {
        // apakah sudah selesai? (finished, failed, denied)
        return $query->whereIn('stage_id', [
            Stage::waitingApprovalStage()->id,
            Stage::sentToSapStage()->id ]);
    }

    public function getPlainIdAttribute()
    {
        return 'attendance-' . $this->id;
    }    
}