<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\ClientException;

class XenForoHelper
{
    const METHOD_GET = 'get';
    const METHOD_POST = 'post';
    const NEWS_BOARD_ID = '4';

    public static function sendRequest(
        $endpoint,
        $data = [],
        $method = self::METHOD_GET
    ) {
        if (config('xenforo.apikey') == null) {
            return ['errors' => []];
        }
        if (is_string($data)) {
            $method = $data;
            $data = [];
        }
        $url = config('xenforo.base_url') . $endpoint;
        $response = Http::withHeaders([
            'XF-Api-Key' => config('xenforo.apikey'),
        ])->$method($url, $data);
        $decodedResponse = json_decode($response, true);
        return $decodedResponse;
    }

    public static function getNewsItems()
    {
        $data = self::sendRequest(
            '/forums/' . self::NEWS_BOARD_ID . '/threads'
        );

        if (array_key_exists('errors', $data)) {
            return ['threads' => []];
        }
        return $data;
    }

    public static function getUserCount()
    {
        $data = self::sendRequest('/users');
        if (array_key_exists('errors', $data)) {
            throw new \Exception('CAN NOT COUNT USERS!');
        }
        return $data['pagination']['total'];
    }

    public static function postAuth($login, $password)
    {
        $credentials = [
            'login' => $login,
            'password' => $password,
        ];

        $client = new Client([
            'base_uri' => config('xenforo.base_url'),
        ]);

        try {
            $response = $client->post('/forum/api/auth', [
                'form_params' => $credentials,
                'headers' => [
                    'XF-Api-Key' => config('xenforo.apikey'),
                    'Accept' => 'application/json',
                ],
            ]);
        } catch (ClientException $e) {
            $data = json_decode(
                $e
                    ->getResponse()
                    ->getBody()
                    ->getContents(),
                true
            );
            return [
                'error' => true,
                'message' => $data['errors'][0]['message'],
            ];
        }

        $data = $response->getBody();
        $data = json_decode($data, true);

        return ['success' => true, 'user' => $data['user']];
    }
}
