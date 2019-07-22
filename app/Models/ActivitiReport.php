<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ActivitiReport extends Model
{
    protected $table = 'activiti_report';
    protected $primaryKey = 'Pernr';
    public $timestamps = false;

    public function scopeMonthList($query)
    {
        return $query->selectRaw('MONTH(tanggal) as month')
            ->orderBy(DB::raw('MONTH(tanggal)'), 'desc')
            ->groupBy( DB::raw('MONTH(tanggal)') );
        # code...
    }

    public function scopeYearList($query)
    {
        return $query->selectRaw('YEAR(tanggal) as year')
            ->orderBy(DB::raw('YEAR(tanggal)'), 'desc')
            ->groupBy( DB::raw('YEAR(tanggal)') );
        # code...
    }
}
