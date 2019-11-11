<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PeriodDates;
use App\Traits\ReceiveStage;
use App\Traits\OfLoggedUser;
use Illuminate\Support\Facades\DB;

class OtherActivity extends Model
{
    use PeriodDates, ReceiveStage, OfLoggedUser;
    protected $casts = [
        'id' => 'integer',
        'personnel_no' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'stage_id' => 'integer',
        'other_activity_profile_id' => 'string',
        'keterangan' => 'string',
    ];

    public function user()
    {
        // many-to-one relationship dengan User
        return $this->belongsTo('App\User', 'personnel_no', 'personnel_no');
    }
    
    public function stage()
    {
        // many-to-one relationship dengan Stage
        return $this->belongsTo('App\Models\Stage')->withDefault();
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\OtherActivityProfile','other_activity_profile_id');
    }

    public function scopeMonthList($query)
    {
        return $query->selectRaw('MONTH(start_date) as month')
            ->orderBy(DB::raw('MONTH(start_date)'), 'asc')
            ->groupBy( DB::raw('MONTH(start_date)') );
        # code...
    }

    public function scopeYearList($query)
    {
        return $query->selectRaw('YEAR(start_date) as year')
            ->orderBy(DB::raw('YEAR(start_date)'), 'asc')
            ->groupBy( DB::raw('YEAR(start_date)') );
        # code...
    }
    public function getPlainIdAttribute()
    {
        return 'other-activity-' . $this->id;
    }

}
