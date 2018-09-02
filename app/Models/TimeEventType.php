<?php

namespace App\Models;

use Eloquent as Model;

class TimeEventType extends Model
{
    public $fillable = [
        'tet',
        'text'
    ];

    protected $casts = [
        'id' => 'integer',
        'tet' => 'string',
        'description' => 'string'
    ];
    public static $rules = [
        
    ];

    public function timeEvents()
    {
        return $this->hasMany('App\Models\TimeEvent');
    }
}
