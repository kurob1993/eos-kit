<?php

namespace App\Models\SAP;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OfLoggedUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Waker extends Model
{
    use OfLoggedUser;
    protected $table = 'wkl_waker';
    public $timestamps = false;
    
    public function getChecktimeAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y H:i:s');
    }

    public function scopefoundYear($query)
    {
        $query->selectRaw('YEAR(checktime) as year')
            ->orderBy(DB::raw('YEAR(checktime)'), 'desc')
            ->groupBy( DB::raw('YEAR(checktime)') );        
    }

    public function scopeMonthYearOf($query, $m, $y)
    {
        return $query->whereMonth('checktime', $m)
            ->whereYear('checktime', $y);
    }    
}
