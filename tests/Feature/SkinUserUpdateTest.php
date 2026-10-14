<?php

use App\Models\GamejoltAccount;
use App\Models\Like;
use App\Models\Skin;
use App\Models\User;

test('skin user update assigns the gamejolt user and rewrites likes once', function () {
    $user = User::factory()->create();
    $account = GamejoltAccount::factory()->create([
        'user_id' => $user->id,
    ]);
    $skin = Skin::factory()->create([
        'user_id' => null,
        'owner_id' => $account->id,
    ]);
    $otherSkin = Skin::factory()->create([
        'user_id' => null,
        'owner_id' => $account->id,
    ]);
    $unchanged = Skin::factory()->create([
        'user_id' => $user->id,
        'owner_id' => $account->id,
    ]);

    $likes = collect([
        Like::query()->create([
            'user_id' => $account->id,
            'likeable_id' => $skin->id,
            'likeable_type' => $skin->getMorphClass(),
        ]),
        Like::query()->create([
            'user_id' => $account->id,
            'likeable_id' => $otherSkin->id,
            'likeable_type' => $otherSkin->getMorphClass(),
        ]),
    ]);

    $this->artisan('p3d:skinuserupdate')
        ->assertSuccessful();

    expect($skin->fresh()->user_id)->toBe($user->id)
        ->and($otherSkin->fresh()->user_id)->toBe($user->id)
        ->and($unchanged->fresh()->user_id)->toBe($user->id)
        ->and(Like::query()->whereKey($likes->pluck('id'))->pluck('user_id')->map(fn ($id) => (int) $id)->unique()->values()->all())->toBe([$user->id]);
});

test('skin user update leaves skins without a gamejolt account unchanged', function () {
    $skin = Skin::factory()->create([
        'user_id' => null,
        'owner_id' => 424242,
    ]);

    $this->artisan('p3d:skinuserupdate')
        ->assertSuccessful();

    expect($skin->fresh()->user_id)->toBeNull();
});
