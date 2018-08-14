<?php

namespace App\Models;

use Eloquent as Model;
use App\Models\Status;
class AbsenceApproval extends Model
{

    public $fillable = [ 'absence_id', 'regno', 'sequence', 'status_id', 'text' ];

    protected $casts = [
        'id' => 'integer',
        'absence_id' => 'integer',
        'regno' => 'integer',
        'sequence' => 'integer',
        'status_id' => 'integer',
        'text' => 'string'
    ];

    public static $rules = [ ];

    public function user()
    {
        // many-to-one relationship dengan User
        return $this->belongsTo('App\User', 'personnel_no', 'personnel_no');
    }

    public function absence()
    {
        return $this->belongsTo(\App\Models\Absence::class);
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\Status::class);
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
