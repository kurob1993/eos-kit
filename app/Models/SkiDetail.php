<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkiDetail extends Model
{
    public function ski()
    {
        // many-to-one relationship dengan ski
        return $this->belongsTo('\App\Models\Ski');
    }
}
