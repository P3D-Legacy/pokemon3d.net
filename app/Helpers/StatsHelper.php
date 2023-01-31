<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class StatsHelper
{
    const METHOD_GET = 'get';

    const METHOD_POST = 'post';

    public static function countForumMembers(): int
    {
        try {
            return XenForoHelper::getUserCount();
        } catch (\Exception $exception) {
            return 0;
        }
    }

    public static function countPlayers(): int
    {
        try {
            $data = self::sendRequest('/server/status');
            if (isset($data['players'])) {
                return count($data['players']);
            }

            return 0;
        } catch (\Exception $exception) {
            return 0;
        }
    }

    public static function getInGameSeason(): string
    {
        $season = date('W') % 4;
        $seasonName = 'spring';
        if ($season == 0) {
            $seasonName = __('fall');
        } elseif ($season == 1) {
            $seasonName = __('winter');
        } elseif ($season == 2) {
            $seasonName = __('spring');
        } elseif ($season == 3) {
            $seasonName = __('summer');
        }

        return $seasonName;
    }

    public static function sendRequest($endpoint, $data = [], $method = self::METHOD_GET)
    {
        if (config('gameserver.base_url') == null) {
            return ['errors' => []];
        }
        if (is_string($data)) {
            $method = $data;
            $data = [];
        }

        $url = config('gameserver.base_url').$endpoint;
        $response = Http::withHeaders([])->$method($url, $data);
        return json_decode($response, true);
    }
}
