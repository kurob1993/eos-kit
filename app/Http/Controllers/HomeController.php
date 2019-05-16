<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // user yang sedang login
            $this->user = Auth::user()->employee;

            return $next($request);
        });
    }

    public function index()
    {
        if ($this->user->isManager() || $this->user->isGeneralManager()) {
            return redirect()
                ->route('dashboard.index');
        } else {
            return redirect()
                ->route('cvs.index');
        }
    }

    public function noRole()
    {
        return view('errors.403');
    }
}