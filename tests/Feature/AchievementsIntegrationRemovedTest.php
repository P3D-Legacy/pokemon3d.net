<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Inertia\Testing\AssertableInertia as Assert;

test('achievement tables have been removed', function () {
    expect(Schema::hasTable('achievement_details'))->toBeFalse();
    expect(Schema::hasTable('achievement_progress'))->toBeFalse();
});

test('member profiles do not expose achievements', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->get(route('member.show', $user->username))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('members/show')
            ->missing('member.achievements'));
});
