<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Torann\GeoIP\Location;

class UpdateUserTimezone
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle($event)
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

        if ($user->timezone != $geoip_info['timezone']) {
            if (config('timezone.overwrite') == true || $user->timezone == null) {
                $user->timezone = $geoip_info['timezone'] ?? $geoip_info->time_zone['name'];
                $user->save();
            }
        }
    }

}
