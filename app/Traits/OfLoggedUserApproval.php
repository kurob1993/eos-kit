<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait OfLoggedUserApproval
{
    public function scopeOfLoggedUser($query)
    {
        $query->where('regno', Auth::user()->personnel_no);
    }
}