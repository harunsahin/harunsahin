<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ActiveUserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->is_active) {
            auth()->logout();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hesabınız aktif değil.'
                ], 403);
            }
            
            return redirect()->route('login')
                ->with('error', 'Hesabınız aktif değil. Lütfen yönetici ile iletişime geçin.');
        }

        return $next($request);
    }
} 