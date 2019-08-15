<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transition extends Model
{
    public function zhrom0007()
    {
        return $this->hasMany('App\Models\SAP\Zhrom0007','AbbrPosition','abbr_jobs');
    }

    public function zhrom0013()
    {
        return $this->hasMany('App\Models\SAP\Zhrom0013','nojabatan','abbr_jobs');
    }
    
    public function structJab()
    {
        return $this->hasMany('App\Models\SAP\StructJab','no','abbr_jobs');
    }

    public function user()
    {
        return $this->hasMany('App\User','personnel_no','personnel_no');
    }
}
