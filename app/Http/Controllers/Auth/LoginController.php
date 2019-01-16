<?php

namespace App\Http\Controllers\Auth;

use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\Models\StructDisp;
use App\Models\Employee;
use App\Models\Secretary;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as performLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function redirectTo()
    {
        if (Auth::guard('secr')->check()) {
            return '/secretary';
        } else 
            return '/';
    }

    public function logout(Request $request)
    {
        // do the normal logout
        $this->performLogout($request);
        
        // redirecto to sso
        return redirect()->away('https://sso.krakatausteel.com');
    }
        
    public function programaticallyEmployeeLogin(Request $request, $personnel_no, $email)
    {
        $personnel_no = base64_decode($personnel_no);
        $email = base64_decode($email);

        try {
            // find all the details
            $user = User::where('personnel_no', $personnel_no)->first();
            $employee = Employee::where('personnel_no', $personnel_no)->first();

            // find the employee details
            $structDisp = StructDisp::structOf($personnel_no)
                ->selfStruct()
                ->firstOrFail();

            if (is_null($user)) {    
                // create the new user
                $user = new User();
                $user->email = $email;
                $user->personnel_no = $structDisp->empnik;
                $user->name = $structDisp->empname;
                $user->password = Hash::make(str_random(32));
                $user->save();
            }

            if (is_null($employee)) {
                // create the employee
                $employee = new Employee();
                $employee->personnel_no = $structDisp->empnik;
                $employee->name = $structDisp->empname;
                $employee->esgrp = $structDisp->emppersk;
                $employee->cost_ctr = $structDisp->empkostl;
                $employee->position_name = $structDisp->emppostx;
                $employee->org_unit_name = $structDisp->emportx;
                $employee->save();
            }
            
            // attach the role if not available
            if (!$user->hasRole('employee')) {
                $employeeRole = Role::where('name', 'employee')->first();
                $user->attachRole($employeeRole);
            }

            // Programmatically login user
            Auth::login($user);

        } catch (ModelNotFoundException $e) {
            
            return 'Employee not found!';
        }

        return $this->sendLoginResponse($request);
    }

    public function programaticallySecretaryLogin(Request $request, $email)
    {
        $email = base64_decode($email);

        try {
            // find all the details
            $user = Secretary::where('email', $email)->firstOrFail();

        } catch (ModelNotFoundException $e) {
            
            return 'Secretary not found!';
        }

        if (!$user->hasRole('secretary')) {
            $secretary = Role::where('name', 'secretary')->first();
            $user->attachRole($secretary);
        }
        // Programmatically login user secretary
        Auth::guard('secr')->login($user);

        return $this->sendLoginResponse($request);
    }
}