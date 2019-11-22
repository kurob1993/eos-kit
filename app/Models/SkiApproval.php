<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ReceiveStatus;
use App\Traits\ParentStage;
use App\Traits\OfLoggedUserApproval;
use App\Traits\FormatDates;

class SkiApproval extends Model
{
    use ReceiveStatus, ParentStage, OfLoggedUserApproval, FormatDates;

    public $fillable = [ 'ski_id', 'regno', 'sequence', 'status_id', 'text' ];

    protected $casts = [
        'id' => 'integer',
        'ski_id' => 'integer',
        'regno' => 'integer',
        'sequence' => 'integer',
        'status_id' => 'integer',
        'text' => 'string'
    ];

    public function user()
    {
        // many-to-one relationship dengan User
        return $this->belongsTo('App\User', 'regno', 'personnel_no');
    }
    
    public function ski()
    {
        // many-to-one relationship dengan ski
        return $this->belongsTo('\App\Models\Ski');
    }

    public function employee()
    {
        // many-to-one relationship dengan Employee
        return $this->belongsTo('App\Models\Employee', 'regno', 'personnel_no');
    }

    public function status()
    {
        // one-to-one relationship dengan status
        return $this->belongsTo('\App\Models\Status');
    }
}
