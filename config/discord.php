<?php

return [
    'token' => env('DISCORD_TOKEN', null),
    'server_id' => (int)env('DISCORD_SERVER_ID', null),
    'invite_url' => env('DISCORD_INVITE_URL', '#'),
];