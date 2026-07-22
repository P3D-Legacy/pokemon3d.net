<?php

use App\Exceptions\GameJoltDataStoreFetchException;
use App\Jobs\SyncGameSaveForUser;
use App\Jobs\SyncGameSaveGamejoltAccountTrophies;
use App\Models\GamejoltAccount;
use App\Models\GameSave;
use App\Models\User;
use App\Services\GameJoltDataStoreGateway;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Mockery\MockInterface;

it('skips missing datastore keys and still syncs later columns', function () {
    $user = User::factory()->create();
    $account = GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->mock(GameJoltDataStoreGateway::class, function (MockInterface $mock) {
        $mock->shouldReceive('fetch')
            ->andReturnUsing(function (string $key) {
                if (str_ends_with($key, '|apricorns')) {
                    return [
                        'response' => [
                            'success' => 'false',
                            'message' => 'No item with that key could be found.',
                        ],
                    ];
                }

                if (str_ends_with($key, '|player')) {
                    return [
                        'response' => [
                            'success' => 'true',
                            'data' => "Name|Ash\r\n",
                        ],
                    ];
                }

                return [
                    'response' => [
                        'success' => 'false',
                        'message' => 'No item with that key could be found.',
                    ],
                ];
            });
    });

    SyncGameSaveForUser::dispatchSync($user->fresh());

    $gameSave = GameSave::where('user_id', $account->user_id)->first();

    expect($gameSave)->not->toBeNull()
        ->and($gameSave->player)->toBe("Name|Ash\r\n")
        ->and($gameSave->apricorns)->toBe('');
});

it('skips unsuccessful datastore keys that are not known hard failures', function () {
    $user = User::factory()->create();
    $account = GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->mock(GameJoltDataStoreGateway::class, function (MockInterface $mock) {
        $mock->shouldReceive('fetch')
            ->andReturnUsing(function (string $key) {
                if (str_ends_with($key, '|apricorns')) {
                    return [
                        'response' => [
                            'success' => 'false',
                            'message' => 'Unknown fatal error occurred.',
                        ],
                    ];
                }

                if (str_ends_with($key, '|player')) {
                    return [
                        'response' => [
                            'success' => 'true',
                            'data' => "Name|Ash\r\n",
                        ],
                    ];
                }

                return [
                    'response' => [
                        'success' => 'false',
                    ],
                ];
            });
    });

    SyncGameSaveForUser::dispatchSync($user->fresh());

    $gameSave = GameSave::where('user_id', $account->user_id)->first();

    expect($gameSave)->not->toBeNull()
        ->and($gameSave->player)->toBe("Name|Ash\r\n")
        ->and($gameSave->apricorns)->toBe('');
});

it('aborts without retrying when stored GameJolt credentials are rejected', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->mock(GameJoltDataStoreGateway::class, function (MockInterface $mock) {
        $mock->shouldReceive('fetch')
            ->once()
            ->andReturn([
                'response' => [
                    'success' => 'false',
                    'message' => 'No such user with the credentials passed in could be found.',
                ],
            ]);
    });

    SyncGameSaveForUser::dispatchSync($user->fresh());

    expect(GameSave::where('user_id', $user->id)->exists())->toBeFalse();
});

it('throws on hard GameJolt datastore failures', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->mock(GameJoltDataStoreGateway::class, function (MockInterface $mock) {
        $mock->shouldReceive('fetch')
            ->once()
            ->andReturn([
                'response' => [
                    'success' => 'false',
                    'message' => 'Game ID is invalid or not specified.',
                ],
            ]);
    });

    expect(fn () => SyncGameSaveForUser::dispatchSync($user->fresh()))
        ->toThrow(GameJoltDataStoreFetchException::class);
});

it('is unique with retries and backoff configured', function () {
    $user = User::factory()->create();

    $saveJob = new SyncGameSaveForUser($user);
    $trophyJob = new SyncGameSaveGamejoltAccountTrophies($user);

    expect($saveJob)->toBeInstanceOf(ShouldBeUnique::class)
        ->and($saveJob->uniqueId())->toBe((string) $user->id)
        ->and($saveJob->tries)->toBe(3)
        ->and($saveJob->backoff)->toBe([1, 5, 10])
        ->and($saveJob->uniqueFor)->toBe(300)
        ->and($trophyJob)->toBeInstanceOf(ShouldBeUnique::class)
        ->and($trophyJob->uniqueId())->toBe((string) $user->id)
        ->and($trophyJob->tries)->toBe(3)
        ->and($trophyJob->backoff)->toBe([1, 5, 10])
        ->and($trophyJob->uniqueFor)->toBe(300);
});
