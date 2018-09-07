<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Stage;
use App\Traits\FormatDates;
use App\Traits\PeriodDates;
use App\Traits\ReceiveStage;


class Attendance extends Model
{
    use FormatDates, PeriodDates, ReceiveStage;

    public $fillable = [
        'personnel_no',
        'start_date',
        'end_date',
        'attendance_type_id',
        'stage_id'
    ];

    protected $casts = [
        'id' => 'integer',
        'personnel_no' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'attendance_type_id' => 'integer',
        'stage_id' => 'integer'
    ];

    public static $rules = [
        
    ];

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
    
    public function scopeIncompleted($query)
    {
        // apakah sudah selesai? (finished, failed, denied)
        return $query->whereIn('stage_id', [
            Stage::waitingApprovalStage()->id,
            Stage::sentToSapStage()->id ]);
    }

    public function permitType()
    {
        // column aliasing for attendanceType
        return $this->belongsTo('App\Models\AttendanceType');
    }    
}