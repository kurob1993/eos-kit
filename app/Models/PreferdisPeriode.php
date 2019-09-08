<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreferdisPeriode extends Model
{
    protected $fillable = ['id','periode','start_date','finish_date','status'];
    
    public function preferdises()
    {
        return $this->hasMany('App\Models\Preferdis');
    }
}
