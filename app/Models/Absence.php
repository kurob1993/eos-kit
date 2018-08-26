<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Config;
use App\Models\Stage;

class Absence extends Model
{
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

    public function scopeIncompleted($query)
    {
        // apakah sudah selesai? (finished, failed, denied)
        return $query->whereIn('stage_id', [
            Stage::waitingApprovalStage()->id,
            Stage::sentToSapStage()->id ]);
    }

    public function scopeIntersectWith($query, $s, $e)
    {
        /*
        // apakah ada data absence yang beririsan (intersection)
        // SELECT id, personnel_no, start_date, end_date FROM venus.absences
        // WHERE (start_date <= $s AND end_date >= $s) 
        // OR (start_date <= $e AND end_date >= $e)
        */
        return $query->where(function ($query) use($s){ 
            $query->where('start_date', '<=', $s)->where('end_date', '>=', $s);
        })
        ->orWhere(function ($query) use($e){ 
            $query->where('start_date', '<=', $e)->where('end_date', '>=', $e); 
        }); 
    }

    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format(config('emss.date_format'));
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->end_date->format(config('emss.date_format'));
    }

    public function getFormattedPeriodAttribute()
    {
        return $this->formattedStartDate . '-' . $this->formattedEndDate;
    }

    public function getDeductionAttribute()
    {
        // Jumlah pengajuan cuti dalam hari
        return $this->end_date->diffInDays($this->start_date) + 1;
    }

    public function getIsSuccessAttribute()
    {
        // apakah absence ini tahapnya sudah success
        return ($this->stage_id == Stage::successStage()->id) ?
            true : false;
    }

    public function getIsSentToSapAttribute()
    {
        // apakah absence ini tahapnya sent to SAP
        return ($this->stage_id == Stage::sentToSapStage()->id) ?
            true : false;
    }

    public function getIsFailedAttribute()
    {
        // apakah absence ini tahapnya failed
        return ($this->stage_id == Stage::FailedStage()->id) ?
            true : false;
    }    

    public function getIsDeniedAttribute()
    {
        // apakah absence ini tahapnya failed
        return ($this->stage_id == Stage::FailedStage()->id) ?
            true : false;
    }    
}
