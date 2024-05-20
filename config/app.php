<?php

return [

    'lang_contribution_url' => env('LANG_CONTRIBUTION_URL', '/'),

    'consents' => [
        'tos.1' => 'Terms of Service &mdash; updated 2021-07-28',
        'email.newsletter' => 'E-mail: Receive an e-mail when we update the game or website',
        'email.notifications' => 'E-mail: Receive an e-mail if you have unread notifications',
    ],

    'required_consent' => 'tos.1',

];
