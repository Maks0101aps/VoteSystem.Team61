<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return Redirect::guest(URL::route('login'));
        }

        if (! Auth::user()->email_verified_at && 
            ! $request->routeIs('verification.*') && 
            ! $request->routeIs('logout')) {
            return Redirect::guest(URL::route('verification.show'));
        }

        return $next($request);
    }
}
