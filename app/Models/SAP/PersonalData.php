<?php

namespace App\Models\SAP;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SapOfLoggedUser;

class PersonalData extends Model
{
    use SapOfLoggedUser;
    
    protected $table = 'it0002';
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
