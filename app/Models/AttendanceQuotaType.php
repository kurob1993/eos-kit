<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceQuotaType extends Model
{
    public $fillable = [
        'subtype',
        'text'
    ];

    protected $casts = [
        'id' => 'integer',
        'subtype' => 'string',
        'text' => 'string'
    ];

    public static $rules = [
        
    ];

    public function attendanceQuotas()
    {
        return $this->hasMany('App\Models\AttendanceQuota');
    }
}
