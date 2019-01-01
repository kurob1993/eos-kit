<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait OfLoggedUser
{
    public function scopeOfLoggedUser($query)
    {
        $query->where('personnel_no', Auth::user()->personnel_no);
    }
}