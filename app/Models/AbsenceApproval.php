<?php

namespace App\Models;

use Eloquent as Model;
use App\Traits\ReceiveStatus;

class AbsenceApproval extends Model
{
    use ReceiveStatus;

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
        return $this->belongsTo('App\User', 'regno', 'personnel_no');
    }

    public function absence()
    {
        // many-to-one relationship dengan absence
        return $this->belongsTo('\App\Models\Absence');
    }

    public function status()
    {
        // one-to-one relationship dengan status
        return $this->belongsTo('\App\Models\Status');
    }

    public function scopeLeavesOnly($query)
    {
        // Querying Relationship Existence
        return $query->whereHas('absence', function ($query){
            $query->leavesOnly();
        });
    }
}
