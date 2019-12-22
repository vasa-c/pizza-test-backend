<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class CanBeAdmin
{
    public function handle($request, Closure $next, $guard = null)
    {
        /** @var User $user */
        $user = Auth::guard($guard)->user();
        if ($user === null) {
            return response()->json([], 403);
        }
        if (!$user->isAdmin()) {
            return response()->json([], 403);
        }
        return $next($request);
    }
}
