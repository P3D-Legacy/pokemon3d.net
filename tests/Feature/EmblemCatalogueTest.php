<?php

use App\Models\GamejoltAccount;
use App\Models\GamejoltAccountTrophy;
use App\Models\GameSave;
use App\Models\User;
use App\Support\EmblemCatalogue;

it('always unlocks trainer when the asset exists', function () {
    $user = User::factory()->create();

    expect(EmblemCatalogue::unlockedFor($user))->toContain('trainer');
});

it('unlocks emblems from game save achievements and achieved trophies', function () {
    $user = User::factory()->create();
    $account = GamejoltAccount::factory()->create(['user_id' => $user->id]);

    GameSave::factory()->create([
        'user_id' => $user->id,
        'player' => "Name|Ash\r\nEarnedAchievements|champion,eevee\r\n",
    ]);

    GamejoltAccountTrophy::factory()->achieved()->create([
        'gamejolt_account_id' => $account->id,
        'id' => 1962, // kanto
        'title' => 'Kanto',
    ]);

    GamejoltAccountTrophy::factory()->create([
        'gamejolt_account_id' => $account->id,
        'id' => 1963, // johto, not achieved
        'title' => 'Johto',
        'achieved' => false,
    ]);

    $unlocked = EmblemCatalogue::unlockedFor($user->fresh());

    expect($unlocked)->toContain('trainer')
        ->and($unlocked)->toContain('champion')
        ->and($unlocked)->toContain('eevee')
        ->and($unlocked)->toContain('kanto')
        ->and($unlocked)->not->toContain('johto');
});

it('resolves the effective cover from override then gamejolt emblem', function () {
    $user = User::factory()->create([
        'gamejolt_emblem' => 'trainer',
        'profile_background' => null,
    ]);

    expect(EmblemCatalogue::effectiveSlug($user))->toBe('trainer')
        ->and(EmblemCatalogue::coverImageUrl($user))->toEndWith('/img/emblems/trainer.png');

    $user->forceFill(['profile_background' => 'champion'])->save();

    expect(EmblemCatalogue::effectiveSlug($user->fresh()))->toBe('champion');
});
