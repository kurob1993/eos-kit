<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsenceSapResponse extends Model
{
    public function absences()
    {
        // one-to-many relationship dengan Absence
        return $this->belongsTo('\App\Models\Absence','reqno');
    }
}
