<?php

use App\Models\GamejoltAccount;
use App\Models\GameSave;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('members index includes default filters', function () {
    User::factory()->create([
        'username' => 'listedtrainer',
        'last_active_at' => now()->subHour(),
    ]);

    $this->get(route('member.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('members/index')
            ->where('filters.search', '')
            ->where('filters.sort', 'last_active')
            ->where('filters.gamejolt', false)
            ->where('filters.gamesave', false)
            ->has('members.data', 1)
            ->where('members.per_page', 24));
});

test('members index search narrows by username', function () {
    User::factory()->create(['username' => 'ashketchum', 'last_active_at' => now()]);
    User::factory()->create(['username' => 'mistywater', 'last_active_at' => now()->subMinute()]);

    $this->get(route('member.index', ['search' => 'ash']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('members/index')
            ->where('filters.search', 'ash')
            ->has('members.data', 1)
            ->where('members.data.0.username', 'ashketchum'));
});

test('members index search matches location', function () {
    User::factory()->create([
        'username' => 'brockrock',
        'location' => 'Pewter City',
        'last_active_at' => now(),
    ]);
    User::factory()->create([
        'username' => 'oakprofessor',
        'location' => 'Pallet Town',
        'last_active_at' => now()->subMinute(),
    ]);

    $this->get(route('member.index', ['search' => 'Pewter']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('members.data', 1)
            ->where('members.data.0.username', 'brockrock'));
});

test('members index filters by game jolt account', function () {
    $withGamejolt = User::factory()->create([
        'username' => 'linkedtrainer',
        'last_active_at' => now(),
    ]);
    GamejoltAccount::factory()->create(['user_id' => $withGamejolt->id]);

    User::factory()->create([
        'username' => 'plaintrainer',
        'last_active_at' => now()->subMinute(),
    ]);

    $this->get(route('member.index', ['gamejolt' => 1]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.gamejolt', true)
            ->has('members.data', 1)
            ->where('members.data.0.username', 'linkedtrainer')
            ->where('members.data.0.has_gamejolt', true));
});

test('members index filters by game save', function () {
    $withSave = User::factory()->create([
        'username' => 'savertrainer',
        'last_active_at' => now(),
    ]);
    GameSave::factory()->create(['user_id' => $withSave->id]);

    User::factory()->create([
        'username' => 'nosavetrainer',
        'last_active_at' => now()->subMinute(),
    ]);

    $this->get(route('member.index', ['gamesave' => 1]))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.gamesave', true)
            ->has('members.data', 1)
            ->where('members.data.0.username', 'savertrainer')
            ->where('members.data.0.has_game_save', true));
});

test('members index sorts by last active by default', function () {
    User::factory()->create([
        'username' => 'olderactive',
        'last_active_at' => now()->subDay(),
    ]);
    User::factory()->create([
        'username' => 'neweractive',
        'last_active_at' => now(),
    ]);

    $this->get(route('member.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('members.data.0.username', 'neweractive')
            ->where('members.data.1.username', 'olderactive'));
});

test('members index sorts by newest joined', function () {
    User::factory()->create([
        'username' => 'veteran',
        'created_at' => now()->subYears(2),
        'last_active_at' => now(),
    ]);
    User::factory()->create([
        'username' => 'rookie',
        'created_at' => now()->subDay(),
        'last_active_at' => now()->subHour(),
    ]);

    $this->get(route('member.index', ['sort' => 'joined']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.sort', 'joined')
            ->where('members.data.0.username', 'rookie')
            ->where('members.data.1.username', 'veteran'));
});

test('members index sorts by oldest joined', function () {
    User::factory()->create([
        'username' => 'veteran',
        'created_at' => now()->subYears(2),
        'last_active_at' => now(),
    ]);
    User::factory()->create([
        'username' => 'rookie',
        'created_at' => now()->subDay(),
        'last_active_at' => now()->subHour(),
    ]);

    $this->get(route('member.index', ['sort' => 'joined_oldest']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.sort', 'joined_oldest')
            ->where('members.data.0.username', 'veteran')
            ->where('members.data.1.username', 'rookie'));
});

test('members index sorts by username ascending and descending', function () {
    User::factory()->create(['username' => 'charlie', 'last_active_at' => now()]);
    User::factory()->create(['username' => 'alpha', 'last_active_at' => now()->subMinute()]);
    User::factory()->create(['username' => 'bravo', 'last_active_at' => now()->subMinutes(2)]);

    $this->get(route('member.index', ['sort' => 'username']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.sort', 'username')
            ->where('members.data.0.username', 'alpha')
            ->where('members.data.1.username', 'bravo')
            ->where('members.data.2.username', 'charlie'));

    $this->get(route('member.index', ['sort' => 'username_desc']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.sort', 'username_desc')
            ->where('members.data.0.username', 'charlie')
            ->where('members.data.1.username', 'bravo')
            ->where('members.data.2.username', 'alpha'));
});

test('members index excludes unverified users', function () {
    User::factory()->unverified()->create([
        'username' => 'unverifiedtrainer',
        'last_active_at' => now(),
    ]);
    User::factory()->create([
        'username' => 'verifiedtrainer',
        'last_active_at' => now()->subMinute(),
    ]);

    $this->get(route('member.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('members.data', 1)
            ->where('members.data.0.username', 'verifiedtrainer'));
});

test('members index falls back to last active for invalid sort', function () {
    User::factory()->create([
        'username' => 'olderactive',
        'last_active_at' => now()->subDay(),
    ]);
    User::factory()->create([
        'username' => 'neweractive',
        'last_active_at' => now(),
    ]);

    $this->get(route('member.index', ['sort' => 'not-a-real-sort']))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('filters.sort', 'last_active')
            ->where('members.data.0.username', 'neweractive')
            ->where('members.data.1.username', 'olderactive'));
});
