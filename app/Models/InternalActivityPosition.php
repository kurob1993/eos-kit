<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalActivityPosition extends Model
{
    protected $casts = [
        'id' => 'string',
        'name' => 'string'
    ];

    public function internalActivity()
    {
        return $this->hasMany('App\Models\InternalActivity');
    }
}
