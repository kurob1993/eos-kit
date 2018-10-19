<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $table = 'it0021';
    protected $primaryKey = 'ID';
    public $incrementing = false;
    public $timestamps = false;

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
