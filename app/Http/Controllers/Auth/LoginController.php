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
    
    public function logout(Request $request)
    {
        // do the normal logout
        $this->performLogout($request);
        
        // redirecto to sso
        return redirect()->away('https://sso.krakatausteel.com');
    }
        
    public function programaticallyLogin($personnel_no, $email)
    {
        $personnel_no = base64_decode($personnel_no);
        $email = base64_decode($email);

        try {
            // find all the details
            $user = User::where('personnel_no', $personnel_no)->first();
            $employee = Employee::where('personnel_no', $personnel_no)->first();

            if (is_null($user) || is_null($employee)) {

                // find the employee details
                $structDisp = StructDisp::structOf($personnel_no)
                    ->selfStruct()
                    ->firstOrFail();
                    
                // create the new user
                $user = new User();
                $user->email = $email;
                $user->personnel_no = $structDisp->empnik;
                $user->name = $structDisp->empname;
                $user->password = Hash::make(str_random(32));
                
                // create the employee
                $employee = new Employee();
                $employee->personnel_no = $structDisp->empnik;
                $employee->name = $structDisp->empnik;
                $employee->esgrp = $structDisp->emppersk;
                $employee->cost_ctr = $structDisp->empkostl;
                $employee->position_name = $structDisp->emppostx;
                $employee->org_unit_name = $structDisp->emportx;
                
                $user->save();
                $employee->save();
                
                // attach the role
                $employeeRole = Role::where('name', 'employee')->first();
                $user->attachRole($employeeRole);

            }

            // Programmatically login user
            Auth::login($user);
            
            // redirect to dashboards
            return redirect()->route('dashboards.employee');            

        } catch (ModelNotFoundException $e) {
            
            return 'employee not found';
        }
    }
}