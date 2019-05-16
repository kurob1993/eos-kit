<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;

class EmployeeEmailController extends Controller
{
    protected function findByPersonnel($personnel_no)
    {
        // menampilkan informasi karyawan
        return User::findByPersonnel($personnel_no)
            ->first();
    }

    public function index()
    {
        $email = User::select('email','personnel_no')
            ->groupBy('personnel_no')
            ->get();
        return $email;
    }

    public function show($personnel_no)
    {
        // menampilkan informasi karyawan
        $employee =  User::select('email','personnel_no')->where('personnel_no',$personnel_no )->get();

        if (!is_null($employee))
            return $employee;
        else
            return []; 
    
    }
}
