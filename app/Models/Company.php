<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function companyPosisitions() 
    {
        return $this->hasMany('App\Models\CompanyPosisition');
    }
}
