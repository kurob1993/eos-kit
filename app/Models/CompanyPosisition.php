<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPosisition extends Model
{
    public function preferdis()
    {
        return $this->hasMany('\App\Models\Preferdis');
    }
}
