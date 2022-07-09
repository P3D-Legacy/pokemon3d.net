<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class WikiHelper
{
    public static function sendRequest($endpoint)
    {
        if (config('wiki.api_url') === null) {
            return 'Wiki API URL is not set';
        }
        $url = config('wiki.api_url') . '?' . $endpoint;
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])
            ->get($url)
            ->body();

        return json_decode($response);
    }

    public static function getSearchResults(string $query)
    {
        if (empty($query)) {
            return collect([]);
        }
        $endpoint =
            'action=query&format=json&list=search&indexpageids=1&iwurl=1&srsearch=' .
            $query .
            '&srnamespace=0&srprop=size%7Cwordcount%7Ctimestamp%7Csnippet&srsort=relevance';

        return self::sendRequest($endpoint);
    }

    public static function getAllPages(): Collection
    {
        $endpoint =
            'action=query&format=json&list=allpages&indexpageids=1&iwurl=1&apnamespace=0&apfilterredir=nonredirects&aplimit=500&apdir=ascending';

        return collect(self::sendRequest($endpoint)->query->allpages);
    }
}
