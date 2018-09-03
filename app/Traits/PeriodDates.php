<?php

namespace App\Traits;

trait PeriodDates
{
    protected $newDateFormat = 'd.m.Y H:i';

    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format($this->newDateFormat);
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->end_date->format($this->newDateFormat);
    }

    public function getFormattedPeriodAttribute()
    {
        return $this->formattedStartDate . '-' . $this->formattedEndDate;
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
}