<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'discord' => [
        'client_id' => env('DISCORD_CLIENT_ID'),
        'client_secret' => env('DISCORD_CLIENT_SECRET'),
        'redirect' => env('DISCORD_REDIRECT_URI', '/login/discord/callback'),
        'token' => env('DISCORD_TOKEN'),
        'server_id' => env('DISCORD_SERVER_ID'),
        'invite_url' => env('DISCORD_INVITE_URL', '#'),
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect' => env('TWITTER_REDIRECT_URI', '/login/twitter/callback'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI', '/login/facebook/callback'),
    ],

    'twitch' => [
        'client_id' => env('TWITCH_CLIENT_ID'),
        'client_secret' => env('TWITCH_CLIENT_SECRET'),
        'redirect' => env('TWITCH_REDIRECT_URI', '/login/twitch/callback'),
    ],

    'gamejolt' => [
        'game_id' => env('GAMEJOLT_GAME_ID'),
        'private_key' => env('GAMEJOLT_GAME_PRIVATE_KEY'),
    ],

    'xenforo' => [
        'api_key' => env('XENFORO_API_KEY'),
        'base_url' => env('XENFORO_BASE_URL'),
        'api_url' => env('XENFORO_BASE_URL').'/api',
    ],

    'wiki' => [
        'base_url' => env('WIKI_BASE_URL'),
        'api_url' => env('WIKI_BASE_URL').'/api.php',
    ],

];
