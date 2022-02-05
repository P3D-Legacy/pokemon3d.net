<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiJsonMiddleware
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
        $request->headers->add(["accept" => "application/json"]);
        return $next($request);
    }
}
