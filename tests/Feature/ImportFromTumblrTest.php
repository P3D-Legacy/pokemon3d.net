<?php

use Illuminate\Console\Command;

it('rejects missing or empty Tumblr credentials', function (mixed $credentials) {
    config()->set('services.tumblr', $credentials);

    $this->artisan('import:tumblr', ['blogname' => 'pokemon3d'])
        ->expectsOutput('Please set the tumblr environment variables.')
        ->assertExitCode(Command::FAILURE);
})->with([
    'missing configuration' => null,
    'empty credential' => [[
        'consumer_key' => '',
        'consumer_secret' => 'secret',
        'oauth_token' => 'token',
        'oauth_token_secret' => 'token-secret',
    ]],
]);
