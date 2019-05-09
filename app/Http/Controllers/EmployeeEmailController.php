<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class EmployeeEmailController extends Controller
{
    protected function findByPersonnel($personnel_no)
    {
        // menampilkan informasi karyawan
        return Users::findByPersonnel($personnel_no)
            ->first();
    }

    public function index()
    {
        $email = Users::select('email','personnel_no')
            ->groupBy('personnel_no')
            ->get();
        return $email;
    }

    public function show($personnel_no)
    {
        // menampilkan informasi karyawan
        $employee =  \App\User::select('email','personnel_no')->where('personnel_no',$personnel_no )->get();

        if (!is_null($employee))
            return $employee;
        else
            return []; 
    
    }
}
