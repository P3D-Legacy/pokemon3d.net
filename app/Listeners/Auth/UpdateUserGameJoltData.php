<?php

namespace App\Listeners\Auth;

use Artisan;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

class UpdateUserGameJoltData
{
    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
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

        // Check if user has gamejolt account
        if (! $user->gamejolt) {
            return;
        }

        // Run commands to update user data
        Artisan::call('gj:update-trophies', [
            'gamejolt_user_id' => $user->gamejolt->id,
        ]);
        Artisan::call('sync:gamesave', [
            'gamejolt_user_id' => $user->gamejolt->id,
        ]);
        Artisan::call('sync:game-save-gamejolt-account-trophies', [
            'gamejolt_user_id' => $user->gamejolt->id,
        ]);
    }
}
