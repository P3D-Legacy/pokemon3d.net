<?php

use App\Models\GamejoltAccount;
use App\Models\GameSave;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests are redirected from my save', function () {
    $this->get(route('save.index'))
        ->assertRedirect(route('login'));
});

test('authenticated user without gamejolt is redirected from my save', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('save.index'))
        ->assertRedirect(route('profile.show'))
        ->assertSessionHas('flash.bannerStyle', 'warning');
});

test('authenticated user with gamejolt can open my save without a synced save', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('save.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('save/index')
            ->where('gameSave.available', false));
});

test('authenticated user with game save receives full owner payload', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);
    GameSave::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('save.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('save/index')
            ->where('gameSave.available', true)
            ->has('gameSave.party')
            ->has('gameSave.box')
            ->has('gameSave.items')
            ->has('gameSave.daycare')
            ->has('gameSave.hall_of_fame')
            ->has('gameSave.roaming')
            ->has('gameSave.apricorns')
            ->has('gameSave.berries')
            ->has('gameSave.itemdata'));
});
