<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

trait SapMasterData
{
    protected $simple_date_format = 'd/m/y';

    public function scopeSapOfLoggedUser($query)
    {
        $query->where('PERNR', Auth::user()->personnel_no);
    }

    public function scopeLastEndDate($query)
    {
        $query->where('ENDDA', '9999-12-31');
    }
    
    public function getBegdaAttribute($value)
	{
		return Carbon::parse($value)->format($this->simple_date_format);
    }
    
	public function getEnddaAttribute($value)
	{
		return Carbon::parse($value)->format($this->simple_date_format);
    }    
}