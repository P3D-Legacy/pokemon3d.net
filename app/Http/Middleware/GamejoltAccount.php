<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GamejoltAccount
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
        if (!$request->user()->gamejolt) {
            $request->session()->flash('flash.bannerStyle', 'info');
            $request
                ->session()
                ->flash(
                    'flash.banner',
                    'You need to link your GameJolt account before accessing this page.'
                );
            return redirect()->route('profile.show');
        }
        return $next($request);
    }
}
