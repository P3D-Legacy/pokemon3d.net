<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\GJUser;
use Illuminate\Http\Request;

class GamejoltAdmin
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
            redirect()->route('login')->with('error', 'Gamejolt API keys is not set by the admin!');
        }
        if (GJUser::where('gjid', $request->session()->get('gjid'))->first()->is_admin === false) {
            // If user is not a admin, redirect home
            return redirect()->route('home')->with('warning', 'You do not have access to this page!');
        }
        return $next($request);
    }
}
