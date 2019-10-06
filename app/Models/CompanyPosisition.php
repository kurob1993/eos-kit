<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPosisition extends Model
{
    public function company() 
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function preferdis()
    {
        return $this->hasMany('App\Models\Preferdis');
    }
}
