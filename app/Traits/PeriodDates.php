<?php

namespace App\Traits;
use Illuminate\Support\Facades\DB;

trait PeriodDates
{
    protected $periodDateFormat = 'd.m.Y';

    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format($this->periodDateFormat);
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->end_date->format($this->periodDateFormat);
    }

    public function getFormattedPeriodAttribute()
    {
        return $this->formattedStartDate . '-' . $this->formattedEndDate;
    }

    public function getPeriodAttribute()
    {
        return $this->start_date . '-' . $this->end_date;
    }
    
    public function getDurationAttribute()
    {
        return $this->end_date->diffInDays($this->start_date) + 1;
    }

    public function getHourDurationAttribute()
    {
        return $this->duration * 8;
    }

    public function scopeIntersectWith($query, $s, $e)
    {
        // apakah ada data absence yang beririsan (intersection)
        // SELECT id, personnel_no, start_date, end_date FROM absences
        // WHERE (start_date <= $s AND end_date >= $s) 
        // OR (start_date <= $e AND end_date >= $e)
        return $query->where(function ($query) use($s){ 
            $query->where('start_date', '>=', $s)->orWhere('end_date', '>=', $s);
        })
        ->where(function ($query) use($e){ 
            $query->where('start_date', '<=', $e)->orWhere('end_date', '<=', $e); 
        }); 
    }

    public function scopeMonthYearPeriodOf($query, $m, $y, $p)
    {
        return $query->monthYearOf($m, $y)
            ->where('personnel_no', $p);
    }
    public function scopeMonthYearOf($query, $m, $y)
    {
        return $query->whereMonth('start_date', $m)
            ->whereYear('start_date', $y);
    }

    public function scopeCurrentPeriod($query)
    {
        return $query->whereMonth('start_date', date('m'))
            ->whereYear('start_date', date('Y'));
    }

    public function scopeCurrentYearPeriod($query)
    {
        return $query->whereYear('start_date', date('Y'));
    }

    public function scopefoundYear($query)
    {
        $query->selectRaw('YEAR(start_date) as year')
            ->orderBy(DB::raw('YEAR(start_date)'), 'desc')
            ->groupBy( DB::raw('YEAR(start_date)') );        
    }

    public function scopefoundMonth($query)
    {
        $query->selectRaw('MONTH(start_date) as month')
            ->orderBy(DB::raw('MONTH(start_date)'), 'desc')
            ->groupBy( DB::raw('MONTH(start_date)') );        
    }
}