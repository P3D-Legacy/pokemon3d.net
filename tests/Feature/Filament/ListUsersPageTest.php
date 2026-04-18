<?php

use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function (): void {
    seedPermissions();
});

test('staff can render filament users table', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('moderator');

    Filament::setCurrentPanel(Filament::getDefaultPanel());

    $this->actingAs($user);

    Livewire::test(ListUsers::class)
        ->assertSuccessful();
});
