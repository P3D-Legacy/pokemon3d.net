<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GameJoltAssociation
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!$request->user()->gamejolt) {
            $request->session()->flash('flash.bannerStyle', 'warning');
            $request
                ->session()
                ->flash(
                    'flash.banner',
                    __(
                        'Please link your Game Jolt account in the section below, you will be able to use the skin section once this is done'
                    )
                );

            return redirect()->route('profile.show');
        }

        return $next($request);
    }
}
