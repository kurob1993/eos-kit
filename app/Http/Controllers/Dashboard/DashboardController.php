<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    protected $user, $subordinates, $chartThemes;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // user yang sedang login
            $this->user = Auth::user()->employee;

            // bawahan dari user yang sedang login
            $this->subordinates = $this->user->subordinates()
                ->pluck('personnel_no', 'name');

            // konfigurasi fusionchart default
            $this->chartThemes = [
                "theme" => "fusion",
                "baseFont" => "Karla",
                "baseFontColor" => "#153957",
                "outCnvBaseFont" => "Karla",
            ];

            return $next($request);
        });
    }

    public function monthNumToText($num)
    {
        return date("F", mktime(0, 0, 0, $num, 1));
    }

    public function index()
    {
        return view('dashboards.employee');
    }

    public function basisDashboard()
    {
        return view('dashboards.basis');
    }

    public function personnelServiceDashboard()
    {
        return view('dashboards.personnel_service');
    }
}
