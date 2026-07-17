<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Lettermint Project Token
    |--------------------------------------------------------------------------
    |
    | Every Lettermint project has a unique project token. You can find your
    | token in your Lettermint project settings.
    |
    */

    'token' => env('LETTERMINT_PROJECT_TOKEN', env('LETTERMINT_TOKEN')),

    /*
    |--------------------------------------------------------------------------
    | Lettermint API Token
    |--------------------------------------------------------------------------
    |
    | The Lettermint Team API uses a bearer API token. Use this token when
    | interacting with projects, domains, routes, messages, and webhooks.
    |
    */

    'api_token' => env('LETTERMINT_TEAM_TOKEN', env('LETTERMINT_API_TOKEN')),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The maximum number of seconds to wait for a response from the Lettermint
    | API before timing out. Increase this if you send large batch requests
    | that occasionally need longer than the default to be accepted.
    |
    */

    'timeout' => (int) env('LETTERMINT_TIMEOUT', 15),

    /*
    |--------------------------------------------------------------------------
    | Webhooks Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the webhook endpoint settings for receiving events from
    | Lettermint. You can find your webhook signing secret in your
    | Lettermint project settings under the Webhooks section.
    |
    */

    'webhooks' => [

        /*
        |--------------------------------------------------------------------------
        | Webhook Secret
        |--------------------------------------------------------------------------
        |
        | The signing secret used to verify incoming webhook requests. This
        | ensures that the webhook payload is from Lettermint and hasn't
        | been tampered with.
        |
        */

        'secret' => env('LETTERMINT_WEBHOOK_SECRET'),

        /*
        |--------------------------------------------------------------------------
        | Webhook Route Prefix
        |--------------------------------------------------------------------------
        |
        | The route prefix for the webhook endpoint. The full URL will be:
        | {your-app-url}/{prefix}/webhook
        |
        | Default: lettermint (results in /lettermint/webhook)
        |
        */

        'prefix' => env('LETTERMINT_WEBHOOK_PREFIX', 'lettermint'),

        /*
        |--------------------------------------------------------------------------
        | Timestamp Tolerance
        |--------------------------------------------------------------------------
        |
        | The maximum allowed time difference (in seconds) between the webhook
        | timestamp and the current time. This helps prevent replay attacks.
        |
        | Default: 300 (5 minutes)
        |
        */

        'tolerance' => env('LETTERMINT_WEBHOOK_TOLERANCE', 300),

    ],

];
