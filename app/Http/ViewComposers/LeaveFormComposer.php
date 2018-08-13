<?php

namespace App\http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\User;
use App\Models\AbsenceQuota;
use Session;

class LeaveFormComposer
{
    private $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function compose(View $view)
    {
        
    }
}
