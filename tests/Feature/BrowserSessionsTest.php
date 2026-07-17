<?php

use App\Models\User;

test('other browser sessions can be logged out', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('other-browser-sessions.destroy'), [
            'password' => 'password',
        ])
        ->assertRedirect();
});
