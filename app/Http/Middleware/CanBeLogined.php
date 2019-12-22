<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CanBeLogined
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->user() === null) {
            return response()->json([], 403);
        }
        return $next($request);
    }
}
