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
}
