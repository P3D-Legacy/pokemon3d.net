<?php

namespace App\Services;

use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;

class GameJoltDataStoreGateway
{
    /**
     * @return array{response?: array{success?: mixed, data?: mixed, message?: string|null}}
     */
    public function fetch(string $key, string $username, string $token): array
    {
        $api = new GamejoltApi(new GamejoltConfig(
            config('services.gamejolt.game_id'),
            config('services.gamejolt.private_key'),
        ));

        return $api->dataStore()->fetch($key, $username, $token);
    }
}
