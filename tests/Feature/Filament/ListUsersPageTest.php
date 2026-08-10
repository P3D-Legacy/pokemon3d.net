<?php

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    seedPermissions();
    Filament::setCurrentPanel(Filament::getDefaultPanel());
});

test('super admin can render filament users table', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('super-admin');

    $this->actingAs($user);

    Livewire::test(ListUsers::class)
        ->assertSuccessful();
});

test('regular users cannot access filament users table', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test(ListUsers::class)
        ->assertForbidden();
});

test('moderators cannot access filament users table without manage.users', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('moderator');

    $this->actingAs($user);

    Livewire::test(ListUsers::class)
        ->assertForbidden();
});

test('super admin can create a user with roles', function () {
    $admin = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('super-admin');

    $this->actingAs($admin);

    $moderatorRole = Role::findByName('moderator');

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Filament User',
            'username' => 'filament_user',
            'email' => 'filament-user@example.com',
            'password' => 'Password123!',
            'gender' => 0,
            'roles' => [$moderatorRole->getKey()],
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $created = User::query()->where('email', 'filament-user@example.com')->first();

    expect($created)->not->toBeNull()
        ->and($created->hasRole('moderator'))->toBeTrue();
});

test('super admin can edit a user', function () {
    $admin = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('super-admin');

    $user = User::factory()->create();

    $this->actingAs($admin);

    Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
        ->fillForm([
            'name' => 'Updated Name',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect($user->refresh()->name)->toBe('Updated Name');
});
