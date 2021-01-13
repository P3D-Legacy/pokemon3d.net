<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GamejoltAuth
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
        if (!env("GAMEJOLT_GAME_ID") || !env("GAMEJOLT_GAME_PRIVATE_KEY")) {
            redirect()->route('login')->with('error', 'Gamejolt API keys is not set by an admin!');
        }
        // TODO: Check if user is logged in or not
        return $next($request);
    }
}
