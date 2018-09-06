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
        // one-to-many relationship dengan AttendanceQuota
        return $this->hasMany('App\Models\AttendanceQuota');
    }

    public function scopeSuratPerintahLembur($query)
    {
        // NEED TO IMPLEMENT CONFIGURATION
        return $query->find(1);
    }    
}
