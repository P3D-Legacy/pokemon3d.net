<?php

use App\Models\GamejoltAccount;
use App\Models\GamejoltAccountTrophy;
use App\Models\GameSave;
use App\Models\Pokedex;
use App\Models\User;
use App\Support\GameSavePresenter;
use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia as Assert;

test('game save presenter returns the full pokedexes shape', function () {
    $user = User::factory()->create();

    GamejoltAccount::factory()->create([
        'user_id' => $user->id,
    ]);

    GameSave::factory()->create([
        'user_id' => $user->id,
        'pokedex' => "{1|2}\r\n{4|1}\r\n{7|0}\r\n{25|2}",
    ]);

    Pokedex::factory()->create([
        'name' => 'National Pokédex',
        'slug' => 'pokedex_national',
        'pokemon_ids' => ['1', '4', '7', '25'],
    ]);

    $payload = GameSavePresenter::forUser($user->fresh());

    expect($payload['available'])->toBeTrue()
        ->and($payload['caught_count'])->toBe(2)
        ->and($payload['seen_count'])->toBe(3)
        ->and($payload['pokedexes'])->toHaveCount(1)
        ->and($payload['pokedexes'][0]['slug'])->toBe('pokedex_national')
        ->and($payload['pokedexes'][0]['entries'])->toHaveCount(4)
        ->and($payload['pokedexes'][0]['entries'][0])->toMatchArray([
            'id' => '1',
            'name' => 'Bulbasaur',
            'seen' => true,
            'caught' => true,
        ])
        ->and($payload['pokedexes'][0]['entries'][1])->toMatchArray([
            'id' => '4',
            'name' => 'Charmander',
            'seen' => true,
            'caught' => false,
        ])
        ->and($payload['pokedexes'][0]['entries'][2])->toMatchArray([
            'id' => '7',
            'name' => 'Squirtle',
            'seen' => false,
            'caught' => false,
        ])
        ->and($payload['pokedexes'][0]['entries'][3])->toMatchArray([
            'id' => '25',
            'name' => 'Pikachu',
            'seen' => true,
            'caught' => true,
        ]);
});

test('members show includes pokedexes when a game save is available', function () {
    $user = User::factory()->create([
        'username' => 'dextrainer',
    ]);

    GamejoltAccount::factory()->create([
        'user_id' => $user->id,
    ]);

    GameSave::factory()->create([
        'user_id' => $user->id,
        'pokedex' => "{1|2}\r\n{4|1}",
    ]);

    Pokedex::factory()->create([
        'name' => 'Kanto Pokédex',
        'slug' => 'pokedex_kanto',
        'pokemon_ids' => ['1', '4'],
    ]);

    $this->get(route('member.show', $user->username))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('members/show')
            ->where('member.gameSave.available', true)
            ->where('member.gameSave.caught_count', 1)
            ->where('member.gameSave.seen_count', 2)
            ->has('member.gameSave.pokedexes', 1)
            ->where('member.gameSave.pokedexes.0.slug', 'pokedex_kanto')
            ->where('member.gameSave.pokedexes.0.caught_count', 1)
            ->where('member.gameSave.pokedexes.0.seen_count', 2)
            ->where('member.gameSave.pokedexes.0.entries.0.id', '1')
            ->where('member.gameSave.pokedexes.0.entries.0.name', 'Bulbasaur')
            ->where('member.gameSave.pokedexes.0.entries.0.seen', true)
            ->where('member.gameSave.pokedexes.0.entries.0.caught', true)
            ->where('member.gameSave.pokedexes.0.entries.1.id', '4')
            ->where('member.gameSave.pokedexes.0.entries.1.caught', false));
});

test('game save presenter returns trophy details including difficulty and image', function () {
    $user = User::factory()->create();

    $gamejolt = GamejoltAccount::factory()->create([
        'user_id' => $user->id,
    ]);

    GameSave::factory()->create([
        'user_id' => $user->id,
    ]);

    GamejoltAccountTrophy::factory()->achieved()->create([
        'gamejolt_account_id' => $gamejolt->id,
        'id' => 101,
        'title' => 'Pokédex',
        'difficulty' => 'Gold',
        'description' => 'Catch them all.',
        'image_url' => 'https://example.com/pokedex.png',
    ]);

    GamejoltAccountTrophy::factory()->create([
        'gamejolt_account_id' => $gamejolt->id,
        'id' => 102,
        'title' => 'UnoDosTres',
        'difficulty' => 'Bronze',
        'description' => 'Start your journey.',
        'image_url' => 'https://example.com/uno.png',
        'achieved' => false,
    ]);

    $payload = GameSavePresenter::forUser($user->fresh());

    expect($payload['trophies']['achieved'])->toBe(1)
        ->and($payload['trophies']['total'])->toBe(2)
        ->and($payload['trophies']['items'])->toHaveCount(2)
        ->and($payload['trophies']['items'][0])->toMatchArray([
            'id' => 101,
            'title' => 'Pokédex',
            'difficulty' => 'Gold',
            'description' => 'Catch them all.',
            'image_url' => 'https://example.com/pokedex.png',
            'achieved' => true,
        ])
        ->and($payload['trophies']['items'][1])->toMatchArray([
            'id' => 102,
            'title' => 'UnoDosTres',
            'difficulty' => 'Bronze',
            'achieved' => false,
        ]);
});

test('game save presenter returns structured party members with sprite urls', function () {
    $user = User::factory()->create();

    GamejoltAccount::factory()->create([
        'user_id' => $user->id,
    ]);

    GameSave::factory()->create([
        'user_id' => $user->id,
    ]);

    $payload = GameSavePresenter::forUser($user->fresh());

    expect($payload['party'])->toHaveCount(2)
        ->and($payload['party'][0])->toMatchArray([
            'id' => 25,
            'name' => 'Pikachu',
            'nickname' => 'Sparky',
            'level' => 15,
            'shiny' => false,
            'is_egg' => false,
            'ability' => 'Static',
            'sprite_url' => 'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Pokemon/Sprites/25.png',
        ])
        ->and($payload['party'][0])->not->toHaveKey('Image')
        ->and($payload['party'][1])->toMatchArray([
            'id' => 1,
            'name' => 'Bulbasaur',
            'nickname' => null,
            'level' => 1,
            'shiny' => true,
            'is_egg' => true,
            'ability' => 'Overgrow',
            'sprite_url' => 'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Pokemon/Egg/Egg_front.png',
        ])
        ->and($payload['party'][1])->not->toHaveKey('Image');
});

test('party ability letter slots resolve from species data without crashing', function () {
    Http::fake([
        'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Pokemon/Data/69.dat' => Http::response(
            implode("\n", [
                'Name|Bellsprout',
                'Ability1|34',
                'Ability2|Nothing',
                'HiddenAbility|82',
            ]),
            200
        ),
        'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Pokemon/Data/95.dat' => Http::response(
            implode("\n", [
                'Name|Onix',
                'Ability1|69',
                'Ability2|5',
                'HiddenAbility|133',
            ]),
            200
        ),
    ]);

    $user = User::factory()->create();

    GamejoltAccount::factory()->create([
        'user_id' => $user->id,
    ]);

    GameSave::factory()->create([
        'user_id' => $user->id,
        'party' => implode("\r\n", [
            '{"Pokemon"[69]}{"Experience"[6169]}{"Gender"[0]}{"EggSteps"[0]}{"NickName"[Breakfast]}{"Level"[20]}{"Ability"[A]}{"Nature"[10]}{"Friendship"[146]}{"isShiny"[0]}',
            '{"Pokemon"[95]}{"Experience"[12161]}{"Gender"[0]}{"EggSteps"[0]}{"NickName"[Rocky]}{"Level"[22]}{"Ability"[B]}{"Nature"[22]}{"Friendship"[174]}{"isShiny"[0]}',
        ]),
    ]);

    $payload = GameSavePresenter::forUser($user->fresh());

    expect($payload['party'])->toHaveCount(2)
        ->and($payload['party'][0])->toMatchArray([
            'id' => 69,
            'name' => 'Bellsprout',
            'nickname' => 'Breakfast',
            'ability' => 'Chlorophyll',
            'nature' => 'Timid',
        ])
        ->and($payload['party'][1])->toMatchArray([
            'id' => 95,
            'name' => 'Onix',
            'nickname' => 'Rocky',
            'ability' => 'Sturdy',
            'nature' => 'Sassy',
        ]);
});

test('get ability falls back safely for unknown numeric and unresolved slots', function () {
    Http::fake([
        'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Pokemon/Data/69.dat' => Http::response(
            "Name|Bellsprout\nAbility1|34\nAbility2|Nothing\nHiddenAbility|82\n",
            200
        ),
    ]);

    $gamesave = GameSave::factory()->create();

    expect($gamesave->getAbility(9))->toBe('Static')
        ->and($gamesave->getAbility(9999))->toBe('???')
        ->and($gamesave->getAbility('B', 69))->toBe('B')
        ->and($gamesave->getAbility('A', 69))->toBe('Chlorophyll');
});

test('game save presenter returns formatted statistics entries', function () {
    $user = User::factory()->create();

    GamejoltAccount::factory()->create([
        'user_id' => $user->id,
    ]);

    GameSave::factory()->create([
        'user_id' => $user->id,
    ]);

    $payload = GameSavePresenter::forUser($user->fresh());

    expect($payload['statistics'])->toHaveCount(4)
        ->and($payload['statistics'][0])->toMatchArray([
            'name' => 'Steps',
            'value' => '12345',
        ])
        ->and($payload['statistics'][1])->toMatchArray([
            'name' => 'BattlesWon',
            'value' => '100',
        ])
        ->and($payload['statistics'][2])->toMatchArray([
            'name' => 'PokemonCaught',
            'value' => '50',
        ])
        ->and($payload['statistics'][3])->toMatchArray([
            'name' => 'PlayTime',
            'value' => '3600',
        ]);
});

test('game save presenter returns structured player details', function () {
    $user = User::factory()->create();

    GamejoltAccount::factory()->create([
        'user_id' => $user->id,
    ]);

    GameSave::factory()->create([
        'user_id' => $user->id,
    ]);

    $payload = GameSavePresenter::forUser($user->fresh());

    expect($payload['details'])->toMatchArray([
        'Name' => 'Red',
        'RivalName' => 'Blue',
        'Location' => 'Pallet Town',
        'Money' => '3000',
        'HasPokedex' => 'Yes',
        'HasPokegear' => 'No',
        'Gender' => 'Male',
        'OT' => '12345',
        'Points' => '150',
        'GTSStars' => '3',
    ]);
});

test('members show includes trophy cards payload when a game save is available', function () {
    $user = User::factory()->create([
        'username' => 'trophytrainer',
    ]);

    $gamejolt = GamejoltAccount::factory()->create([
        'user_id' => $user->id,
    ]);

    GameSave::factory()->create([
        'user_id' => $user->id,
    ]);

    GamejoltAccountTrophy::factory()->achieved()->create([
        'gamejolt_account_id' => $gamejolt->id,
        'id' => 201,
        'title' => 'Pokédex',
        'difficulty' => 'Silver',
        'description' => 'Fill the Pokédex.',
        'image_url' => 'https://example.com/trophy.png',
    ]);

    $this->get(route('member.show', $user->username))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('members/show')
            ->where('member.gameSave.available', true)
            ->where('member.gameSave.trophies.achieved', 1)
            ->where('member.gameSave.trophies.total', 1)
            ->has('member.gameSave.trophies.items', 1)
            ->where('member.gameSave.trophies.items.0.id', 201)
            ->where('member.gameSave.trophies.items.0.title', 'Pokédex')
            ->where('member.gameSave.trophies.items.0.difficulty', 'Silver')
            ->where('member.gameSave.trophies.items.0.image_url', 'https://example.com/trophy.png')
            ->where('member.gameSave.trophies.items.0.achieved', true));
});
