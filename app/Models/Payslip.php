<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\offLoggedUserPay;
use Illuminate\Support\Facades\DB;

class Payslip extends Model
{
    use offLoggedUserPay;
    protected $table = 'slipgaji';
    public $timestamps = false;
    

    public function scopefoundYear($query)
    {
        $query->selectRaw('SUBSTRING(orderan, 1, 4)  as year')
            ->orderBy( DB::raw('SUBSTRING(orderan, 1, 4)'),'asc' )
            ->groupBy ( DB::raw('SUBSTRING(orderan, 1, 4)') );
    }

    public function scopeMonthYearOf($query, $m, $y)
    {
        return $query->whereMonth('checktime', $m)
            ->whereYear('checktime', $y);
    }   
    
    public function scopeTypeYearof($query, $t, $y)
    {
        return $query->where(DB::raw('tipe'),  $t)
        ->where( DB::raw('SUBSTRING(periode, 3, 6)'), $y)
        ->groupBy('tipe','periode');
    }

    public function scopefoundType($query)
    {
        $query->selectRaw('tipe as tipe')
            ->orderBy( DB::raw('tipe'),'asc' )
            ->groupBy( DB::raw('tipe'));
    }

    public function scopefoundPDF($query, $url){
        // PAYSLIP_DIR
        $query->where(DB::raw('namafile'),  $url);
    }
}


