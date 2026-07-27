<?php

namespace App\Jobs;

use App\Models\GamejoltAccount;
use App\Models\GamejoltAccountTrophy;
use App\Models\User;
use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SyncGameSaveGamejoltAccountTrophies implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    /** @var array<int, int> */
    public array $backoff = [1, 5, 10];

    public int $uniqueFor = 300;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function uniqueId(): string
    {
        return (string) $this->user->id;
    }

    public function handle(): void
    {
        Log::withContext([
            'job' => self::class,
            'user_id' => $this->user->id,
        ]);

        $this->user->refresh();
        $this->user->loadMissing(['gamejolt.trophies', 'gamesave']);

        $gameId = config('services.gamejolt.game_id');
        $privateKey = config('services.gamejolt.private_key');

        if (! $gameId || ! $privateKey) {
            Log::warning('Game save trophy sync aborted: GameJolt credentials are not configured.');

            return;
        }

        if (! $this->user->gamejolt) {
            Log::warning('Game save trophy sync aborted: user has no linked GameJolt account.');

            return;
        }

        $api = new GamejoltApi(new GamejoltConfig($gameId, $privateKey));
        $gamejoltUserId = $this->user->gamejolt->id;
        $gamejoltAccount = GamejoltAccount::firstWhere('id', $gamejoltUserId);

        if (! $gamejoltAccount) {
            Log::warning('Game save trophy sync aborted: GameJolt account record not found.', [
                'gamejolt_user_id' => $gamejoltUserId,
            ]);

            return;
        }

        Log::info('Starting game save trophy sync.', [
            'gamejolt_user_id' => $gamejoltUserId,
        ]);

        $trophies = $api->trophies()->fetch($gamejoltAccount->username, $gamejoltAccount->token);
        $success = $trophies['response']['success'] ?? false;

        if (filter_var($success, FILTER_VALIDATE_BOOLEAN)) {
            $trophyRows = collect($trophies['response']['trophies'] ?? []);

            foreach ($trophyRows as $trophy) {
                $attributes = [
                    'title' => $trophy['title'],
                    'difficulty' => $trophy['difficulty'],
                    'description' => $trophy['description'],
                    'image_url' => $trophy['image_url'],
                ];

                if (array_key_exists('achieved', $trophy)) {
                    $achieved = $trophy['achieved'];
                    $attributes['achieved'] = ! (
                        $achieved === false
                        || $achieved === null
                        || $achieved === 0
                        || $achieved === '0'
                        || $achieved === ''
                        || (is_string($achieved) && strtolower($achieved) === 'false')
                    );
                }

                GamejoltAccountTrophy::updateOrCreate(
                    [
                        'gamejolt_account_id' => $gamejoltUserId,
                        'id' => $trophy['id'],
                    ],
                    $attributes
                );
            }
        } else {
            Log::warning('GameJolt trophy fetch failed.', [
                'gamejolt_user_id' => $gamejoltUserId,
                'api_message' => $trophies['response']['message'] ?? null,
            ]);
        }

        $this->user->unsetRelation('gamejolt');
        $this->user->loadMissing(['gamejolt.trophies', 'gamesave']);

        $gameSave = $this->user->gamesave;
        $userTrophies = $this->user->gamejolt?->trophies;

        if ($gameSave && $userTrophies) {
            $gameSaveAchievements = $gameSave->getAchievements();

            foreach ($gameSaveAchievements as $gameSaveAchievement) {
                if ($gameSaveAchievement == 'unodostres') {
                    $achievementName = 'UnoDosTres';
                } elseif ($gameSaveAchievement == 'pokedex') {
                    $achievementName = 'Pokédex';
                } else {
                    $achievementName = Str::headline($gameSaveAchievement);
                }

                $trophy = $userTrophies->firstWhere('title', $achievementName);

                if ($trophy) {
                    $trophy->achieved = true;
                    $trophy->save();
                }
            }
        }

        Log::info('Finished game save trophy sync.');
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('Game save trophy sync job failed.', [
            'job' => self::class,
            'user_id' => $this->user->id,
            'exception' => $exception?->getMessage(),
            'exception_class' => $exception ? $exception::class : null,
        ]);
    }
}
