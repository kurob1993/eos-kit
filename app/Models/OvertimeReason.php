<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeReason extends Model
{
    public $fillable = [
        'overtimeid',
        'text'
    ];

    protected $casts = [
        'id' => 'integer',
        'overtimeid' => 'string',
        'text' => 'string'
    ];

    public static $rules = [
        
    ];

    public function attendanceQuotas()
    {
        return $this->hasMany('App\Models\AttendanceQuota');
    }
}
