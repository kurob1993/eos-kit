<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        //hapus session ketika akan login
        if($request->is('a/*') || $request->is('b/*')){
            $request->session()->flush();
        }

        if (Auth::guard('secr')->check()) {
            return redirect()->route('secretary.index');
        } else if (Auth::check()) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
