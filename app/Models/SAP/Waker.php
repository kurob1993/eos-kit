<?php

namespace App\Models\SAP;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OfLoggedUser;
use Carbon\Carbon;

class Waker extends Model
{
    use OfLoggedUser;
    protected $table = 'wkl_waker';
    public $timestamps = false;
    
    public function getChecktimeAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y H:i:s');
    }
}
