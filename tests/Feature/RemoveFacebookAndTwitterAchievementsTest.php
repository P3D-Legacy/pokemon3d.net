<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;

test('facebook and twitter achievements are removed from the database', function () {
    expect(
        DB::table('achievement_details')
            ->whereIn('name', ['AssociatedFacebook', 'AssociatedTwitter'])
            ->exists()
    )->toBeFalse();

    expect(
        DB::table('achievement_progress')
            ->whereIn('achievement_id', function ($query): void {
                $query->select('id')
                    ->from('achievement_details')
                    ->whereIn('name', ['AssociatedFacebook', 'AssociatedTwitter']);
            })
            ->exists()
    )->toBeFalse();
});

test('member profiles do not expose facebook or twitter achievements', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->get(route('member.show', $user->username))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('members/show')
            ->where('member.achievements', fn ($achievements): bool => collect($achievements)
                ->pluck('name')
                ->doesntContain(['AssociatedFacebook', 'AssociatedTwitter'])));
});
