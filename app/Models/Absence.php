<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Config;
use App\Models\Stage;
use App\Traits\PeriodDates;
use App\Traits\ReceiveStage;

class Absence extends Model
{
    use PeriodDates, ReceiveStage;

    public $fillable = [
        'personnel_no', 
        'start_date', 
        'end_date', 
        'absence_type_id', 
        'note', 
        'address', 
        'attachment'
    ];

    protected $casts = [
        'id' => 'integer',
        'personnel_no' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'absence_type_id' => 'integer',
        'stage_id' => 'integer',
        'note' => 'string',
        'address' => 'string',
        'attachment' => 'string',
    ];

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
        return $this->belongsTo('App\User', 'personnel_no', 'personnel_no');
    }

    public function employee()
    {
        // many-to-one relationship dengan Employee
        return $this->belongsTo('App\Models\Employee', 'personnel_no', 'personnel_no');
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

    public function permitType()
    {
        // column aliasing untuk absence_type
        return $this->absenceType();
    }

    public function permitApprovals()
    {
        // column aliasing untuk absenceApprovals
        return $this->absenceApprovals();
    }

    public function scopeLeavesOnly($query)
    {
        // Querying Relationship Existence
        return $query->whereHas('absenceType', function ($query){
            $query->where('subtype', '0100')
                ->orWhere('subtype', '0200');
        });
    }

    public function scopeExcludeLeaves($query)
    {
        // Querying Relationship Absence
        return $query->whereDoesntHave('absenceType', function ($query){
            $query->where('subtype', '0100')
                ->orWhere('subtype', '0200');
        });
    }

    public function getDeductionAttribute()
    {
        // Jumlah pengajuan cuti dalam hari
        return $this->end_date->diffInDays($this->start_date) + 1;
    }

    public function getIsALeaveAttribute()
    {
        // jika absence adalah cuti tahunan / cuti besar
        return ($this->absenceType->subtype == '0100' 
            || $this->absenceType->subtype == '0200') ? true : false;
    }

    public function getPlainIdAttribute()
    {
        return 'absence-' . $this->id;
    }
}