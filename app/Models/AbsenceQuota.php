<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

class AbsenceQuota extends Model
{
    public $fillable = [
        'personnel_no',
        'start_date',
        'end_date',
        'absence_type_id',
        'start_deduction',
        'end_deduction',
        'number',
        'deduction'
    ];

    protected $casts = [
        'id' => 'integer',
        'personnel_no' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'absence_type_id' => 'integer',
        'start_deduction' => 'date',
        'end_deduction' => 'date',
        'number' => 'integer',
        'deduction' => 'integer'
    ];

    public static $rules = [  ];

    public function user()
    {
        // many-to-one relationship dengan User
      return $this->belongsTo('App\User', 'personnel_no' );
    }

    public function absenceType()
    {
        // many-to-one relationship dengan AbsenceType
        return $this->belongsTo('App\Models\AbsenceType');
    }

    public function getBalanceAttribute()
    {
        // sisa cuti (jatah cuti dikurangi pemakaian)
        return $this->number - $this->deduction;
    }

    public function scopeActiveAbsenceQuota($query, $p)
    {
        $now = Carbon::now()->toDateTimeString();
        
      // mencari apakah ada kuota cuti untuk hari ini
      return $query->where('start_date', '<', $now)
        ->where('end_date', '>', $now)
        ->where('personnel_no', '=' , $p);
    }

    public function scopeActiveAbsenceQuotaOf($query, $p, $s, $e)
    {
      // mencari apakah ada kuota cuti untuk rentang tanggal
      return $query->where('start_date', '<', $s)
        ->where('end_date', '>', $e)
        ->where('personnel_no', '=' , $p);
    }
}
