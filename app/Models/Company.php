<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function companyPosisition() 
    {
        return $this->hasMany('App\Models\CompanyPosisition');
    }
}
