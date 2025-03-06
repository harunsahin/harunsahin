<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogUserActivity
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
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();
            $path = $request->path();
            $method = $request->method();

            Log::info("User Activity", [
                'user_id' => $user->id,
                'name' => $user->name,
                'path' => $path,
                'method' => $method,
                'ip' => $request->ip()
            ]);
        }

        return $response;
    }
} 