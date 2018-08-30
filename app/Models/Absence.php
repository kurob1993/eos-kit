<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Config;
use App\Models\Stage;
use App\Traits\FormatDates;
use App\Traits\PeriodDates;
use App\Traits\ReceiveStage;

class Absence extends Model
{
    use FormatDates, PeriodDates, ReceiveStage;

    public $fillable = ['personnel_no', 'start_date', 'end_date', 'note', 'address'];

    protected $casts = [
        'id' => 'integer',
        'personnel_no' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'absence_type_id' => 'integer',
        'stage_id' => 'integer',
        'note' => 'string',
        'address' => 'string',
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

    public function scopeIncompleted($query)
    {
        // apakah sudah selesai? (finished, failed, denied)
        return $query->whereIn('stage_id', [
            Stage::waitingApprovalStage()->id,
            Stage::sentToSapStage()->id ]);
    }

    public function scopeIntersectWith($query, $s, $e)
    {
        // apakah ada data absence yang beririsan (intersection)
        // SELECT id, personnel_no, start_date, end_date FROM absences
        // WHERE (start_date <= $s AND end_date >= $s) 
        // OR (start_date <= $e AND end_date >= $e)
        return $query->where(function ($query) use($s){ 
            $query->where('start_date', '<=', $s)->where('end_date', '>=', $s);
        })
        ->orWhere(function ($query) use($e){ 
            $query->where('start_date', '<=', $e)->where('end_date', '>=', $e); 
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
}