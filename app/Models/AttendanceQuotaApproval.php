<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\Status;
use App\Traits\FormatDates;

class AttendanceQuotaApproval extends Model
{
    use FormatDates;

    public $fillable = [ 'attendance_quota_id', 'regno', 'sequence', 'status_id', 'text' ];

    protected $casts = [
        'id' => 'integer',
        'attendance_quota_id' => 'integer',
        'regno' => 'integer',
        'sequence' => 'integer',
        'status_id' => 'integer',
        'text' => 'string'
    ];

    public static $rules = [ ];

    public function user()
    {
        // many-to-one relationship dengan User
        return $this->belongsTo('App\User', 'regno', 'personnel_no');
    }

    public function attendanceQuota()
    {
        // many-to-one relationship dengan attendanceQuota
        return $this->belongsTo('\App\Models\AttendanceQuota');
    }

    public function status()
    {
        // one-to-one relationship dengan status
        return $this->belongsTo('\App\Models\Status');
    }

    public function getIsNotWaitingAttribute()
    {
        // apakah absence approval sudah disetujui ATAU ditolak
        // TRUE apabila sudah setuju ATAU sudah tolak
        // FALSE apabila masih waiting
        return ($this->status_id <> Status::firstStatus()->id) ?
            true : false;
    }
    
    public function getIsApprovedAttribute()
    {
        // apakah absence approval sudah disetujui
        return ($this->status_id == Status::approveStatus()->id) ?
            true : false;
    }

    public function scopeWaitedForApproval($query)
    {
        return $query->where('status_id', Status::firstStatus()->id);
    }
}
