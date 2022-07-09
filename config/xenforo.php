<?php

return [
    'apikey' => env('XENFORO_API_KEY', ''),
    'base_url' => env('XENFORO_BASE_URL', env('XENFORO_BASE_WEB_URL').'/api'),
    'base_web_url' => env('XENFORO_BASE_WEB_URL', '#'),
];
