<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use RestCord\DiscordClient;

class StatsHelper
{

    const METHOD_GET = 'get';
    const METHOD_POST = 'post';

    public static function countForumMembers()
    {
        try {
            $count = XenForoHelper::getUserCount();
            return $count;
        } catch (\Exception $exception){
            return 0;
        }
    }

    public static function countPlayers()
    {
        try {
            $data = self::sendRequest("/server/status");
            return count($data['players']);
        } catch (\Exception $exception){
            return 0;
        }
    }

    public static function getInGameSeason()
    {
        $season = date('W') % 4;
        $seasonName = "spring";
        #echo "Season (WOY % 4): " . $season;
        if($season == 0) {
            $seasonName = "fall";
        } elseif($season == 1) {
            $seasonName = "winter";
        } elseif($season == 2) {
            $seasonName = "spring";
        } elseif($season == 3) {
            $seasonName = "summer";
        }
        return $seasonName;
    }

    public static function sendRequest($endpoint, $data = [], $method = self::METHOD_GET)
    {
        if(config('gameserver.base_url') == null) {
            return ['errors' => []];
        }
        if (is_string($data)) {
            $method = $data;
            $data = [];
        }

        $url = config('gameserver.base_url') . $endpoint;
        $response = Http::withHeaders([
        ])->$method($url, $data);
        $decodedResponse = json_decode($response, true);
        return $decodedResponse;
    }

}