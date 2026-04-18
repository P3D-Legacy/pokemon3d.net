<?php

use App\Models\BanReason;
use App\Models\DiscordAccount;
use App\Models\DiscordBotSetting;
use App\Models\GamejoltAccount;
use App\Models\GamejoltAccountBan;
use App\Models\Post;
use App\Models\User;

beforeEach(function (): void {
    seedPermissions();
    writeOpenApiFixture();
    ensurePublicBadgePngFixture();
});

test('api rejects unauthenticated requests', function () {
    $user = User::factory()->create();

    $this->getJson("/api/v1/user/{$user->id}")
        ->assertUnauthorized();
});

test('api rejects authenticated users missing action permission', function () {
    $subject = User::factory()->create();
    $caller = createApiUserWithPermissions([]);

    $caller->syncPermissions(['api']);

    $this->getJson("/api/v1/user/{$subject->id}")
        ->assertForbidden();
});

test('user show returns resource when permitted', function () {
    $subject = User::factory()->create();
    createApiUserWithPermissions(['user.show']);

    $this->getJson("/api/v1/user/{$subject->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $subject->id);
});

test('ban reasons index returns data when permitted', function () {
    BanReason::factory()->count(2)->create();
    createApiUserWithPermissions(['ban_reason.show']);

    $this->getJson('/api/v1/banreason')
        ->assertOk();
});

test('gamejolt account show returns resource when permitted', function () {
    $account = GamejoltAccount::factory()->create();
    createApiUserWithPermissions(['gamejolt_account.show']);

    $this->getJson("/api/v1/gamejoltaccount/{$account->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $account->id);
});

test('discord account show returns resource when permitted', function () {
    $discord = DiscordAccount::factory()->create();
    createApiUserWithPermissions(['discord_account.show']);

    $this->getJson("/api/v1/discordaccount/{$discord->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $discord->id);
});

test('badges index returns data when permitted', function () {
    createApiUserWithPermissions([]);

    $this->getJson('/api/v1/game/badges')
        ->assertOk()
        ->assertJsonStructure(['data']);
});

test('openapi json returns fixture when permitted', function () {
    createApiUserWithPermissions([]);

    $this->getJson('/api/openapi-json')
        ->assertOk()
        ->assertJsonPath('openapi', '3.0.0');
});

test('gamejolt account ban lifecycle', function () {
    createApiUserWithPermissions([
        'gamejolt_account_ban.show',
        'gamejolt_account_ban.create',
        'gamejolt_account_ban.destroy',
    ]);

    $this->getJson('/api/v1/ban/gamejoltaccount')
        ->assertOk();

    $gamejolt = GamejoltAccount::factory()->create();
    $reason = BanReason::factory()->create();

    $this->postJson('/api/v1/ban/gamejoltaccount', [
        'gamejoltaccount_id' => $gamejolt->aid,
        'reason_id' => $reason->id,
    ])
        ->assertCreated();

    $ban = GamejoltAccountBan::query()->firstOrFail();

    $this->getJson("/api/v1/ban/gamejoltaccount/{$gamejolt->aid}")
        ->assertOk();

    $this->deleteJson("/api/v1/ban/gamejoltaccount/{$ban->uuid}")
        ->assertStatus(202)
        ->assertJsonPath('success', 'Ban was removed!');
});

test('discord bot settings index and update', function () {
    createApiUserWithPermissions([
        'discord_bot_setting.show',
        'discord_bot_setting.update',
    ]);

    $this->getJson('/api/v1/bot/discord/settings')
        ->assertOk();

    DiscordBotSetting::query()->create([
        'category_id' => 1,
        'chat_id' => 2,
        'events_id' => 3,
        'hide_events' => json_encode([]),
    ]);

    $this->putJson('/api/v1/bot/discord/settings/1', [
        'category_id' => 10,
        'chat_id' => 20,
        'events_id' => 30,
        'hide_events' => json_encode(['a' => 1]),
    ])
        ->assertOk()
        ->assertJsonPath('category_id', 10);
});

test('post store creates post when permitted', function () {
    $author = User::factory()->create();
    createApiUserWithPermissions(['post.create']);

    $this->postJson('/api/v1/post', [
        'title' => 'API Post',
        'body' => 'Body text',
        'active' => true,
        'sticky' => false,
        'published_at' => now()->toDateString(),
        'user_id' => $author->id,
    ])
        ->assertCreated()
        ->assertJsonPath('data.title', 'API Post');

    expect(Post::query()->where('title', 'API Post')->exists())->toBeTrue();
});
