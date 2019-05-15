<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
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
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
