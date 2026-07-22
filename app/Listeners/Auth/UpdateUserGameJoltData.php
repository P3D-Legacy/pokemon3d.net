<?php

namespace App\Listeners\Auth;

use App\Jobs\SyncGameSaveForUser;
use App\Jobs\SyncGameSaveGamejoltAccountTrophies;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;

class UpdateUserGameJoltData
{
    /**
     * Handle the event.
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

        // Check if user has gamejolt account
        if (! $user->gamejolt) {
            return;
        }

        Bus::chain([
            new SyncGameSaveForUser($user),
            new SyncGameSaveGamejoltAccountTrophies($user),
        ])->dispatch();
    }
}
