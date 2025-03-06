<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->role || (auth()->user()->role->slug !== 'admin' && auth()->user()->role->slug !== 'super-admin')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu işlem için yetkiniz yok.'
                ], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Bu sayfaya erişim yetkiniz yok.');
        }

        return $next($request);
    }
} 