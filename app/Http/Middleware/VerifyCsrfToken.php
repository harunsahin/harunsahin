<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    public function handle($request, Closure $next)
    {
        if ($request->ajax()) {
            if (!$request->header('X-CSRF-TOKEN')) {
                return response('CSRF token mismatch.', 419);
            }
        }

        return parent::handle($request, $next);
    }
} 