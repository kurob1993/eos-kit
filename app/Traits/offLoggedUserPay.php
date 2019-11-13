<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait offLoggedUserPay
{
    public function scopeoffLoggedUserPay($query)
    {
        $query->where('perno', Auth::user()->personnel_no);
    }
}