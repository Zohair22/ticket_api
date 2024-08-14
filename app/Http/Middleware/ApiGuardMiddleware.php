<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiGuardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated with the 'api' guard
        if (!Auth::guard('api')->check()) {
            return response()->json([
                'status' => 'fail',
                'code' => 401,
                'message' => 'Unauthorized. Please log in.'
            ], 401);
        }
            return $next($request);
    }
}
