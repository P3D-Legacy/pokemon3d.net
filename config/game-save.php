<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Save fix request consent text
    |--------------------------------------------------------------------------
    |
    | Stored against each request when the user accepts staff access to view
    | their synced GameJolt save for support purposes.
    |
    */
    'fix_request_consent_text' => 'I accept that staff may view my synced GameJolt save data to investigate and help with this save fix request.',

    /*
    |--------------------------------------------------------------------------
    | Discord webhook for save fix request ops alerts
    |--------------------------------------------------------------------------
    */
    'discord_webhook' => env('DISCORD_GAME_SAVE_FIX_WEBHOOK'),

    /*
    |--------------------------------------------------------------------------
    | Days of inactivity before a stale Discord reminder
    |--------------------------------------------------------------------------
    */
    'stale_after_days' => 7,

];
