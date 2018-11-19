<?php

namespace App\Models;

use Eloquent as Model;

class Stage extends Model
{

    public $fillable = [
        'description', 'sequence'
    ];

    protected $casts = [
        'id' => 'integer',
        'description' => 'string',
        'sequence' => 'integer'
    ];

    public static $rules = [

    ];

    public function absences()
    {
        return $this->hasMany('App\Models\Absence');
    }

    public function attendances()
    {
        return $this->hasMany('App\Models\Attendance');
    }

    public function flows()
    {
      return $this->belongsToMany('App\Models\Flow')->withPivot('sequence');
    }

    public function scopeWaitingApprovalStage($query)
    {
        // NEED TO IMPLEMENT CONFIGURATION
        return $query->find(1);
    }
    
    public function scopeSentToSapStage($query)
    {
        // NEED TO IMPLEMENT CONFIGURATION
        return $query->find(2);
    }
    
    public function scopeSuccessStage($query)
    {
        // NEED TO IMPLEMENT CONFIGURATION
        return $query->find(3);        
    }

    public function scopeFailedStage($query)
    {
        // NEED TO IMPLEMENT CONFIGURATION
        return $query->find(4);
    }

    public function scopeDeniedStage($query)
    {
        // NEED TO IMPLEMENT CONFIGURATION
        return $query->find(5);
    }

    public function getClassDescriptionAttribute()
    {
        $class = 'default';

        switch ($this->id) {
            case Stage::waitingApprovalStage()->id:
                $class = 'default';
            break;
            case Stage::SentToSapStage()->id:
                $class = 'success';
            break;
            case Stage::SuccessStage()->id:
                $class = 'primary';
            break;
            case Stage::failedStage()->id:
                $class = 'info';
            break;
            case Stage::deniedStage()->id:
                $class = 'danger';
            break;
        }

        return $class;
    }
}