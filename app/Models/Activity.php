<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PeriodDates;
use App\Traits\ReceiveStage;
use App\Traits\OfLoggedUser;

class Activity extends Model
{
    use PeriodDates, ReceiveStage, OfLoggedUser;
    protected $casts = [
        'id' => 'integer',
        'personnel_no' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'stage_id' => 'integer',
        'jenis_kegiatan' => 'string',
        'posisi' => 'string',
        'type' => 'string',
        'keterangan' => 'string',
    ];

    public function stage()
    {
        // many-to-one relationship dengan Stage
        return $this->belongsTo('App\Models\Stage')->withDefault();
    }
}
