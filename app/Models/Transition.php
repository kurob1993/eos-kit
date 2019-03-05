<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transition extends Model
{
    public function zhrom0007()
    {
        return $this->hasMany('App\Models\Zhrom0007','AbbrPosition','abbr_jobs');
    }

    public function user()
    {
        return $this->hasMany('App\User','personnel_no','personnel_no');
    }
}
