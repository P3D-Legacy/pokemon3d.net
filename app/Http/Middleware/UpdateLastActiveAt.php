<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActiveAt
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->user()) {
            if (! $request->user()->last_active_at || $request->user()->last_active_at->isPast()) {
                $request->user()->update([
                    'last_active_at' => now(),
                ]);
            }
        }

        return $next($request);
    }
}
