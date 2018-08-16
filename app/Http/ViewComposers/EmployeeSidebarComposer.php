<?php

namespace App\http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\AbsenceApproval;

class EmployeeSidebarComposer
{
    private $userRepository;

    public function compose(View $view)
    {
        $needApprovals = AbsenceApproval::where('regno', Auth::user()->personnel_no)
            ->waitedForApproval()->get();

        $view->with('count_of_needed_approvals', count($needApprovals));
    }
}
