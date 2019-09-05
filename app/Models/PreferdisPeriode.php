<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreferdisPeriode extends Model
{
    public function preferdises()
    {
        return $this->hasMany('App\Models\Preferdis');
    }
}
