<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Absence extends Model
{
    public $fillable = [ 'personnel_no', 'start_date', 'end_date', 'note', 'address' ];

    protected $casts = [
        'id' => 'integer',
        'personnel_no' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'absence_type_id' => 'integer',
        'stage_id' => 'integer',
        'note' => 'string',
        'address' => 'string'
    ];

    protected $dateFormat = 'Y-m-d H:i:s';

    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope('personnel', function (Builder $builder) {
        //     $personnel_no = Auth::user()->employee()->first()->personnel_no;
        //     $builder->where('personnel_no', $personnel_no);
        // });
    }

    public function user()
    {
        // many-to-one relationship dengan User
      return $this->belongsTo('App\User', 'personnel_no' );
    }

    public function absenceType()
    {
        // many-to-one relationship dengan AbsenceType
        return $this->belongsTo('App\Models\AbsenceType')->withDefault();
    }

    public function stage()
    {
        // many-to-one relationship dengan Stage
        return $this->belongsTo('App\Models\Stage')->withDefault();
    }

    public function absenceApprovals()
    {
        // one-to-many relationship dengan AbsenceApproval
        return $this->hasMany('App\Models\AbsenceApproval');
    }

    public function getDeductionAttribute()
    {
        // Jumlah pengajuan cuti dalam hari
      return $this->end_date->diffInDays($this->start_date) + 1;
    }
}
