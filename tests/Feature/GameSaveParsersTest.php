<?php

use App\Models\GamejoltAccount;
use App\Models\GameSave;
use App\Models\User;
use App\Support\GameSavePresenter;

test('game save parsers extract box items daycare hall of fame and roaming', function () {
    $gamesave = GameSave::factory()->create();

    $box = $gamesave->getBox();
    expect($box)->toHaveCount(3)
        ->and($box[0]['box_index'])->toBe(0)
        ->and($box[0]['position'])->toBe(0)
        ->and($box[0]['pokemon']['id'])->toBe(1)
        ->and($box[1]['pokemon']['id'])->toBe(25)
        ->and($box[2]['box_index'])->toBe(1)
        ->and($box[2]['pokemon']['is_egg'])->toBeTrue();

    $items = $gamesave->getItems();
    expect($items)->toHaveCount(3)
        ->and($items[0])->toMatchArray([
            'id' => '5',
            'name' => 'Pokéball',
            'amount' => 10,
        ]);

    $daycare = $gamesave->getDaycare();
    expect($daycare)->toHaveCount(2)
        ->and($daycare[0]['daycare_id'])->toBe(0)
        ->and($daycare[0]['is_egg'])->toBeFalse()
        ->and($daycare[0]['pokemon']['id'])->toBe(1)
        ->and($daycare[1]['slot'])->toBe('Egg')
        ->and($daycare[1]['is_egg'])->toBeTrue()
        ->and($daycare[1]['pokemon']['id'])->toBe(133);

    $hallOfFame = $gamesave->getHallOfFame();
    expect($hallOfFame)->toHaveCount(1)
        ->and($hallOfFame[0]['id'])->toBe(0)
        ->and($hallOfFame[0]['name'])->toBe('Red')
        ->and($hallOfFame[0]['ot'])->toBe('12345')
        ->and($hallOfFame[0]['pokemon'])->toHaveCount(1)
        ->and($hallOfFame[0]['pokemon'][0]['id'])->toBe(25);

    $roaming = $gamesave->getRoamingPokemon();
    expect($roaming)->toHaveCount(1)
        ->and($roaming[0]['roamer_id'])->toBe('100')
        ->and($roaming[0]['level'])->toBe(40)
        ->and($roaming[0]['pokemon']['id'])->toBe(25);

    $apricorns = $gamesave->getApricorns();
    expect($apricorns)->toHaveCount(2)
        ->and($apricorns[0]['type'])->toBe('tree')
        ->and($apricorns[1]['type'])->toBe('kurt')
        ->and($apricorns[1]['amounts']['red'])->toBe(2);

    $berries = $gamesave->getBerries();
    expect($berries)->toHaveCount(1)
        ->and($berries[0]['berry_id'])->toBe('2000')
        ->and($berries[0]['berry_name'])->toBe('Cheri')
        ->and($berries[0]['berry_count'])->toBe(2)
        ->and($berries[0]['watered_stages'])->toBe(0)
        ->and($berries[0]['map_path'])->toBe('johto\routes\route29.dat');

    $legacyBerries = GameSave::factory()->make([
        'berries' => "{route39.dat|8,0,2|9|1|0|2012,9,21,4,0,0|1}\r\n{route38.dat|13,0,12|16|2|1,0,0,0|2012,9,21,4,0,0|1}",
    ])->getBerries();

    expect($legacyBerries)->toHaveCount(2)
        ->and($legacyBerries[0])->toMatchArray([
            'berry_id' => '2009',
            'berry_name' => 'Sitrus',
            'berry_count' => 1,
            'watered_stages' => 0,
            'map_path' => 'route39.dat',
        ])
        ->and($legacyBerries[1])->toMatchArray([
            'berry_id' => '2016',
            'berry_name' => 'Bluk',
            'berry_count' => 2,
            'watered_stages' => 1,
            'map_path' => 'route38.dat',
        ]);

    $itemData = $gamesave->getItemData();
    expect($itemData)->toHaveCount(2)
        ->and($itemData[0]['item_id'])->toBe('5')
        ->and($itemData[0]['item_name'])->toBe('Pokéball');
});

test('owner presenter includes expanded save fields while public presenter does not', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);
    GameSave::factory()->create(['user_id' => $user->id]);

    $public = GameSavePresenter::forUser($user->fresh());
    $owner = GameSavePresenter::forOwner($user->fresh());

    expect($public['available'])->toBeTrue()
        ->and($public)->not->toHaveKey('box')
        ->and($public)->not->toHaveKey('items')
        ->and($owner['available'])->toBeTrue()
        ->and($owner['box'])->toHaveCount(3)
        ->and($owner['items'])->toHaveCount(3)
        ->and($owner['daycare'])->toHaveCount(2)
        ->and($owner['hall_of_fame'])->toHaveCount(1)
        ->and($owner['roaming'])->toHaveCount(1)
        ->and($owner['itemdata']['count'])->toBe(2);
});
