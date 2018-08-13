<?php

namespace App\Models;

use Eloquent as Model;

class Status extends Model
{

    public $table = 'status';

    public $fillable = [ 'description' ];

    protected $casts = [ 'id' => 'integer', 'description' => 'string' ];
    
    public static $rules = [ ];

    public function absenceApprovals()
    {
        return $this->hasMany(\App\Models\AbsenceApproval::class);
    }

    public function attendanceApprovals()
    {
        return $this->hasMany(\App\Models\AttendanceApproval::class);
    }

    public function scopeFirstStatus($query)
    {
        return $query->find(1);
    }

    public function scopeApproveStatus($query)
    {
        return $query->find(2);
    }

    public function scopeRejectStatus($query)
    {
        return $query->find(3);
    }
}
