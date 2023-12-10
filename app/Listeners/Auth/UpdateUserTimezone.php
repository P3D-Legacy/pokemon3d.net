<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

class UpdateUserTimezone
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(Login $event): void
    {
        $user = null;

        /**
         * If the event is Login, we get the user from the web guard.
         */
        if ($event instanceof Login) {
            $user = Auth::user();
        }

        /**
         * If no user is found, we just return. Nothing to do here.
         */
        if (is_null($user)) {
            return;
        }

        $ip = request()->ip();
        $geoip_info = geoip()->getLocation($ip);
        $timezone = $geoip_info['time_zone']['name'] ?? config('app.timezone');

        if ($user->timezone != $timezone || $user->timezone == null) {
            $user->timezone = $timezone;
            $user->save();
        }
    }
}
