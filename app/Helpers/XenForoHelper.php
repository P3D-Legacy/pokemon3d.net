<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;

class XenForoHelper
{
    const METHOD_GET = 'get';

    const METHOD_POST = 'post';

    public static function sendRequest($endpoint, $data = [], $method = self::METHOD_GET)
    {
        if (!config('services.xenforo.api_key')) {
            return ['errors' => []];
        }
        if (is_string($data)) {
            $method = $data;
            $data = [];
        }
        $url = config('services.xenforo.api_url').$endpoint;
        $response = Http::withHeaders([
            'XF-Api-Key' => config('services.xenforo.api_key'),
        ])->$method($url, $data);
        $decodedResponse = json_decode($response, true);

        return $decodedResponse;
    }

    public static function getUserCount()
    {
        $data = self::sendRequest('/users');
        if ($data) {
            if (array_key_exists('errors', $data)) {
                return 0;
            }

            return $data['pagination']['total'];
        }

        return 0;
    }

    public static function postAuth($login, $password)
    {
        $credentials = [
            'login' => $login,
            'password' => $password,
        ];

        $client = new Client([
            'base_uri' => config('services.xenforo.api_url'),
        ]);

        try {
            $response = $client->post('/api/auth', [
                'form_params' => $credentials,
                'headers' => [
                    'XF-Api-Key' => config('services.xenforo.api_key'),
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
                'message' => $data['errors'][0]['message'] ?? 'Could not find error message.',
            ];
        }

        $data = $response->getBody();
        $data = json_decode($data, true);

        return ['success' => true, 'user' => $data['user']];
    }
}
