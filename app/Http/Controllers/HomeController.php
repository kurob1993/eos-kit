<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laratrust\LaratrustFacade as Laratrust;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // if (Laratrust::hasRole('basis')) return $this->basisDashboard();
        // if (Laratrust::hasRole('employee')) return $this->employeeDashboard();
        // if (Laratrust::hasRole('personnel_service')) return $this->personnelServiceDashboard();
        
        return $this->employeeDashboard();
    }

    public function basisDashboard()
    {
        return view('dashboards.basis');
    }

    public function employeeDashboard()
    {
        return view('dashboards.employee');
    }

    public function personnelServiceDashboard()
    {
        return view('dashboards.personnel_service');
    }
}
