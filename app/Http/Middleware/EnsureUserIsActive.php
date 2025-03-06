<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_active) {
            return $next($request);
        }

        Auth::logout();
        return redirect()->route('login')->with('error', 'Hesabınız aktif değil.');
    }
} 