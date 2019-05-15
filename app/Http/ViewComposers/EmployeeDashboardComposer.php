<?php

namespace App\http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardComposer
{
    public function compose(View $view)
    {
        $view->with('user', Auth::user());
    }
}