<?php

namespace App\Actions\Profile;

use App\Models\GamejoltAccount;
use App\Models\User;
use Harrk\GameJoltApi\Exceptions\TimeOutException;
use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;
use Illuminate\Support\Facades\Artisan;

class LinkGamejoltAccount
{
    public function __construct(private readonly ?GamejoltApi $api = null) {}

    /**
     * Authenticate Game Jolt credentials and associate them with the given user.
     *
     * @return GamejoltAccount|string Account on success, or an error message string.
     */
    public function __invoke(User $user, string $username, string $token): GamejoltAccount|string
    {
        $api = $this->api ?? new GamejoltApi(new GamejoltConfig(
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

        try {
            $fetched = $api->users()->fetch($username, $token);
        } catch (TimeOutException $exception) {
            return $exception->getMessage();
        }

        $gamejoltId = $fetched['response']['users'][0]['id'] ?? null;

        if (! $gamejoltId) {
            return 'Could not fetch Game Jolt account details.';
        }

        $existingById = GamejoltAccount::query()
            ->withTrashed()
            ->where('id', $gamejoltId)
            ->first();

        if ($existingById && $existingById->user_id !== $user->id && ! $existingById->trashed()) {
            return 'This Game Jolt account is associated with another P3D account.';
        }

        $existingByUsername = GamejoltAccount::query()
            ->where('username', $username)
            ->where('user_id', '!=', $user->id)
            ->first();

        if ($existingByUsername) {
            return 'This Game Jolt username is associated with another P3D account.';
        }

        $data = [
            'id' => $gamejoltId,
            'username' => $username,
            'token' => $token,
            'verified_at' => now(),
            'user_id' => $user->id,
        ];

        $account = GamejoltAccount::query()
            ->withTrashed()
            ->where('user_id', $user->id)
            ->first();

        if ($account) {
            $account->restore();
            $account->update($data);
        } elseif ($existingById) {
            $existingById->restore();
            $existingById->update($data);
            $account = $existingById;
        } else {
            $account = GamejoltAccount::query()->create($data);
        }

        Artisan::call('p3d:skinuserupdate');

        return $account->fresh();
    }
}
