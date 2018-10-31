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
        if (Auth::guard($guard)->check()) {
            if (Auth::user()->hasRole('secretary'))
                return redirect()->route('secretary.index');
            if (Auth::user()->hasRole('employee'))
                return redirect()->route('dashboards.employee');
            else
                return redirect()->route('noRole');
        }

        return $next($request);
    }
}
