<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait SapOfLoggedUser
{
    public function scopeSapOfLoggedUser($query)
    {
        $query->where('PERNR', Auth::user()->personnel_no);
    }
}