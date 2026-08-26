<?php

use App\Filament\Resources\BanReasonResource\Pages\CreateBanReason;
use App\Filament\Resources\BanReasonResource\Pages\ListBanReasons;
use App\Filament\Resources\RoleResource\Pages\ListRoles;
use App\Models\BanReason;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function (): void {
    seedPermissions();
    Filament::setCurrentPanel(Filament::getDefaultPanel());
});

test('moderator can list and create ban reasons', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('moderator');

    $this->actingAs($user);

    BanReason::factory()->create();

    Livewire::test(ListBanReasons::class)
        ->assertSuccessful();

    Livewire::test(CreateBanReason::class)
        ->fillForm([
            'name' => 'Cheating',
            'user_id' => $user->getKey(),
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect(BanReason::query()->where('name', 'Cheating')->exists())->toBeTrue();
});

test('super admin can list roles', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('super-admin');

    $this->actingAs($user);

    Livewire::test(ListRoles::class)
        ->assertSuccessful();
});

test('moderator cannot list roles', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('moderator');

    $this->actingAs($user);

    Livewire::test(ListRoles::class)
        ->assertForbidden();
});
