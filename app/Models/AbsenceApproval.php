<?php

namespace App\Models;

use Eloquent as Model;
use App\Traits\ReceiveStatus;
use App\Traits\ParentStage;
use App\Traits\OfLoggedUserApproval;

class AbsenceApproval extends Model
{
    use ReceiveStatus, ParentStage, OfLoggedUserApproval;
    
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

    public function employee()
    {
        // many-to-one relationship dengan Employee
        return $this->belongsTo('App\Models\Employee', 'regno', 'personnel_no');
    }

    public function absence()
    {
        // many-to-one relationship dengan absence
        return $this->belongsTo('\App\Models\Absence');
    }

    public function permit()
    {
        // many-to-one relationship dengan absence
        return $this->absence();
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

    public function scopeExcludeLeaves($query)
    {
        // Querying Relationship Absence
        return $query->whereHas('absence', function ($query){
            $query->excludeLeaves();
        });
    }
}