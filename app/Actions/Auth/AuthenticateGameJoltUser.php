<?php

namespace App\Actions\Auth;

use App\Models\GamejoltAccount;
use App\Models\User;
use Harrk\GameJoltApi\Exceptions\TimeOutException;
use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;

class AuthenticateGameJoltUser
{
    /**
     * Authenticate a user via Game Jolt credentials.
     *
     * @return User|string User on success, or an error message string.
     */
    public function __invoke(string $username, string $token): User|string
    {
        $api = new GamejoltApi(new GamejoltConfig(
            (string) config('services.gamejolt.game_id'),
            (string) config('services.gamejolt.private_key'),
        ));

        try {
            $auth = $api->users()->auth($username, $token);
        } catch (TimeOutException $exception) {
            return $exception->getMessage();
        }

        if (filter_var($auth['response']['success'] ?? false, FILTER_VALIDATE_BOOLEAN) === false) {
            $error = $auth['response']['message'] ?? 'Authentication failed.';

            if ($error === 'No such user with the credentials passed in could be found.') {
                return 'Username and/or token is wrong.';
            }

            return $error;
        }

        $gamejoltAccount = GamejoltAccount::query()->where('username', $username)->first();

        if (! $gamejoltAccount) {
            return 'This Game Jolt Account is not associated with a P3D account yet.';
        }

        $user = $gamejoltAccount->user()->first();

        if (! $user) {
            return 'Could not find the user associated with this Game Jolt Account.';
        }

        $gamejoltAccount->touchVerify();

        return $user;
    }
}
