<?php

namespace App\Models;

use Eloquent as Model;

class AttendanceApproval extends Model
{
    public $fillable = [
        'attendance_id',
        'regno',
        'sequence',
        'status_id',
        'approved',
        'text'
    ];

    protected $casts = [
        'id' => 'integer',
        'attendance_id' => 'integer',
        'regno' => 'integer',
        'sequence' => 'integer',
        'status_id' => 'integer',
        'approved' => 'date',
        'text' => 'string'
    ];

    public static $rules = [

    ];

    public function attendance()
    {
        return $this->belongsTo(\App\Models\Attendance::class);
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\Status::class);
    }
}
