<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalActivityOrganization extends Model
{
    protected $casts = [
        'id' => 'string',
        'name' => 'string'
    ];

    public function ExternalActivity()
    {
        return $this->hasMany('App\Models\ExternalActivity');
    }
}
