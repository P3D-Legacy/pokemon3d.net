<?php

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\GamejoltAccount;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
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

test('super admin can see accounts without a gamejolt link and their email', function () {
    $admin = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('super-admin');

    $userWithoutGamejolt = User::factory()->create([
        'email' => 'no-gamejolt@example.com',
        'email_verified_at' => null,
    ]);
    $userWithGamejolt = User::factory()->create([
        'email' => 'has-gamejolt@example.com',
        'email_verified_at' => now(),
    ]);
    GamejoltAccount::factory()->create(['user_id' => $userWithGamejolt->id]);

    $this->actingAs($admin);

    Livewire::test(ListUsers::class)
        ->assertCanSeeTableRecords([$userWithoutGamejolt, $userWithGamejolt])
        ->assertSee('no-gamejolt@example.com')
        ->filterTable('gamejolt', false)
        ->assertCanSeeTableRecords([$userWithoutGamejolt])
        ->assertCanNotSeeTableRecords([$userWithGamejolt]);
});

test('verification link action is available for unverified users', function () {
    $admin = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $admin->assignRole('super-admin');

    $unverified = User::factory()->create([
        'email_verified_at' => null,
    ]);
    $verified = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->actingAs($admin);

    Livewire::test(ListUsers::class)
        ->assertTableActionVisible('verificationLink', $unverified)
        ->assertTableActionHidden('verificationLink', $verified)
        ->mountTableAction('verificationLink', $unverified)
        ->assertTableActionDataSet(function (array $state) use ($unverified): array {
            expect($state['url'])->toContain('/email/verify/'.$unverified->id.'/')
                ->and(URL::hasValidSignature(Request::create($state['url'])))->toBeTrue();

            return $state;
        });
});

test('email verification url matches the signed verification route', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $url = $user->emailVerificationUrl();

    expect($url)->toContain('/email/verify/'.$user->id.'/')
        ->and(URL::hasValidSignature(Request::create($url)))->toBeTrue();
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
