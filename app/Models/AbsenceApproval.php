<?php

namespace App\Models;

use Eloquent as Model;

class AbsenceApproval extends Model
{

    public $fillable = [ 'absence_id', 'regno', 'sequence', 'status_id', 'approved', 'text' ];

    protected $casts = [
        'id' => 'integer',
        'absence_id' => 'integer',
        'regno' => 'integer',
        'sequence' => 'integer',
        'status_id' => 'integer',
        'approved' => 'date',
        'text' => 'string'
    ];

    public static $rules = [ ];

    public function absence()
    {
        return $this->belongsTo(\App\Models\Absence::class);
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\Status::class);
    }
}
