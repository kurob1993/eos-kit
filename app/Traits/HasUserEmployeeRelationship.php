<?php

namespace App\Traits;

trait HasUserEmployeeRelationship
{
    public function user()
    {
      // many-to-one relationship
      return $this->belongsTo('\App\User', 'PERNR', 'personnel_no');        
    }

    public function employee()
    {
      // many-to-one relationship
      return $this->belongsTo('\App\Models\Employee', 'PERNR', 'personnel_no');
    }
}