<?php

use App\Filament\Widgets\NewUsers;
use App\Filament\Widgets\UsersPerDay;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard;
use Livewire\Livewire;

beforeEach(function (): void {
    seedPermissions();
    Filament::setCurrentPanel(Filament::getDefaultPanel());
});

test('moderator can render the filament dashboard and user widgets', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('moderator');

    $this->actingAs($user);

    Livewire::test(Dashboard::class)
        ->assertSuccessful();

    Livewire::test(NewUsers::class)
        ->assertSuccessful();

    Livewire::test(UsersPerDay::class)
        ->assertSuccessful();
});
