<?php

use App\Filament\Resources\ResourcePackResource\Pages\CreateResourcePack;
use App\Filament\Resources\ResourcePackResource\Pages\ListResourcePacks;
use App\Filament\Resources\ServerResource\Pages\CreateServer;
use App\Filament\Resources\ServerResource\Pages\ListServers;
use App\Filament\Resources\SkinResource\Pages\ListSkins;
use App\Models\Resource;
use App\Models\Server;
use App\Models\Skin;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function (): void {
    seedPermissions();
    Filament::setCurrentPanel(Filament::getDefaultPanel());
});

test('staff can list and create servers', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('moderator');

    $this->actingAs($user);

    Server::factory()->create();

    Livewire::test(ListServers::class)
        ->assertSuccessful();

    Livewire::test(CreateServer::class)
        ->fillForm([
            'name' => 'Test Server',
            'description' => 'A server',
            'host' => 'play.example.com',
            'port' => 25565,
            'active' => true,
            'official' => false,
            'user_id' => $user->getKey(),
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect(Server::query()->where('name', 'Test Server')->exists())->toBeTrue();
});

test('staff can list skins', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('moderator');

    $this->actingAs($user);

    Skin::factory()->create();

    Livewire::test(ListSkins::class)
        ->assertSuccessful();
});

test('staff can list and create resource packs', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('moderator');

    $this->actingAs($user);

    Resource::factory()->create();

    Livewire::test(ListResourcePacks::class)
        ->assertSuccessful();

    Livewire::test(CreateResourcePack::class)
        ->fillForm([
            'name' => 'Cool Pack',
            'brief' => 'Brief',
            'description' => 'Description',
            'user_id' => $user->getKey(),
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect(Resource::query()->where('name', 'Cool Pack')->exists())->toBeTrue();
});
