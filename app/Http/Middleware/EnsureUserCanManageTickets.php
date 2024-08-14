<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EnsureUserCanManageTickets
{
    public function handle(Request $request, Closure $next)
    {
        if (Gate::denies('manage-tickets')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
