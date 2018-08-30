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
}