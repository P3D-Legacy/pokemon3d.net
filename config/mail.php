<?php

return [

    'mailers' => [
        'mailgun' => [
            'transport' => 'mailgun',
            // 'client' => [
            //     'timeout' => 5,
            // ],
        ],

        'lettermint' => [
            'transport' => 'lettermint',
            'route_id' => env('LETTERMINT_ROUTE_ID'),
            'idempotency' => true,
            'idempotency_window' => 86400,
        ],
    ],

];
