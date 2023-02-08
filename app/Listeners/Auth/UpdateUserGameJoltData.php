<?php

namespace App\Listeners\Auth;

use App\Jobs\SyncGameSaveForUser;
use App\Jobs\SyncGameSaveGamejoltAccountTrophies;
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

        // Dispatch job to update user data
        SyncGameSaveForUser::dispatch($user);
        SyncGameSaveGamejoltAccountTrophies::dispatch($user);
    }
}
