<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\PeriodDates;

class AbsenceQuota extends Model
{
    use PeriodDates;
    
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
      return $this->belongsTo('App\User', 'personnel_no', 'personnel_no' );
    }

    public function employee()
    {
        // many-to-one relationship dengan Employee
        return $this->belongsTo('App\Models\Employee', 'personnel_no', 'personnel_no');
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

    public function scopeQuotaNow($query)
    {
        $now = Carbon::now()->toDateTimeString();

        return $query->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now);
    }

    public function scopeQuotaOf($query, $s, $e)
    {
        return $query->where(function($query) use ($s){
            $query->where('start_date','<=',$s)
            ->where('end_date','>=',$s);
        })
        ->where(function($query) use ($e){
            $query->where('start_date','<=',$e)
            ->where('end_date','>=',$e);
        });        
    }

    public function scopeActiveAbsenceQuota($query, $p)
    {
       return $query->quotaNow()
        ->where('personnel_no', '=' , $p);
    }

    public function scopeActiveAbsenceQuotaOf($query, $p, $s, $e)
    {
      // mencari apakah ada kuota cuti untuk rentang tanggal
        return $query->where('personnel_no', '=' , $p)
            ->quotaOf($s, $e);
    }

    public function getPlainIdAttribute()
    {
        return 'abquota-' . $this->id;
    }

    public function getFormattedStartDeductionAttribute()
    {
        return $this->start_date->format($this->periodDateFormat);
    }

    public function getFormattedEndDeductionAttribute()
    {
        return $this->end_date->format($this->periodDateFormat);
    }
}
