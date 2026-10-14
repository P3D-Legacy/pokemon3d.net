<?php

use App\Models\PokemonAbility;
use App\Models\PokemonItem;
use App\Models\PokemonMove;
use App\Models\PokemonSpecies;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

function encyclopediaEditor(): User
{
    seedPermissions();

    $user = User::factory()->create();
    $user->assignRole('wiki-editor');

    return $user;
}

test('pokemon pages render structured fields', function () {
    $species = PokemonSpecies::factory()->create([
        'name' => 'Abra',
        'slug' => 'abra',
        'type_1' => 'Psychic',
    ]);

    $this->get(route('wiki.show', 'abra'))
        ->assertOk()
        ->assertInertia(fn (Assert $inertia) => $inertia
            ->component('wiki/pokemon')
            ->where('entry.title', 'Abra')
            ->where('entry.type_1', 'Psychic')
            ->where('entry.slug', $species->slug));
});

test('move item and ability pages render', function () {
    PokemonMove::factory()->create(['name' => 'Teleport', 'slug' => 'teleport']);
    PokemonItem::factory()->create(['name' => 'Potion', 'slug' => 'potion']);
    PokemonAbility::factory()->create(['name' => 'Synchronize', 'slug' => 'synchronize']);

    $this->get(route('wiki.show', 'teleport'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('wiki/move'));

    $this->get(route('wiki.show', 'potion'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('wiki/item'));

    $this->get(route('wiki.show', 'synchronize'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('wiki/ability'));
});

test('wiki index includes encyclopedia entries', function () {
    PokemonSpecies::factory()->create(['name' => 'Abra', 'slug' => 'abra']);

    $this->get(route('wiki', ['kind' => 'pokemon']))
        ->assertOk()
        ->assertInertia(fn (Assert $inertia) => $inertia
            ->has('entries.data', 1)
            ->where('entries.data.0.kind', 'pokemon'));
});

test('trusted editors can update encyclopedia notes and keep history', function () {
    $species = PokemonSpecies::factory()->create([
        'name' => 'Abra',
        'slug' => 'abra',
        'notes' => 'Old notes',
    ]);

    $this->actingAs(encyclopediaEditor())
        ->put(route('wiki.update', 'abra'), [
            'notes' => 'A fox like psychic Pokémon.',
            'type_1' => 'Psychic',
            'summary' => 'Clarified notes',
        ])
        ->assertRedirect(route('wiki.show', 'abra'));

    expect($species->fresh()->notes)->toBe('A fox like psychic Pokémon.')
        ->and($species->fresh()->revisions)->toHaveCount(1);
});

test('guests cannot edit encyclopedia entries', function () {
    PokemonItem::factory()->create(['slug' => 'potion']);

    $this->get(route('wiki.edit', 'potion'))->assertRedirect(route('login'));
});
