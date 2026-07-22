<?php

namespace App\Jobs;

use App\Exceptions\GameJoltDataStoreFetchException;
use App\Models\GamejoltAccount;
use App\Models\GameSave;
use App\Models\User;
use App\Services\GameJoltDataStoreGateway;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncGameSaveForUser implements ShouldBeUnique, ShouldQueue
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

    public function handle(GameJoltDataStoreGateway $dataStore): void
    {
        Log::withContext([
            'job' => self::class,
            'user_id' => $this->user->id,
        ]);

        Log::info('Starting game save sync.');

        $gameId = config('services.gamejolt.game_id');
        $privateKey = config('services.gamejolt.private_key');

        if (! $gameId || ! $privateKey) {
            Log::warning('Game save sync aborted: GameJolt credentials are not configured.', [
                'has_game_id' => filled($gameId),
                'has_private_key' => filled($privateKey),
            ]);

            return;
        }

        $this->user->loadMissing('gamejolt');

        if (! $this->user->gamejolt) {
            Log::warning('Game save sync aborted: user has no linked GameJolt account.');

            return;
        }

        $gamejoltUserId = $this->user->gamejolt->id;
        $gamejoltAccount = GamejoltAccount::firstWhere('id', $gamejoltUserId);

        if (! $gamejoltAccount) {
            Log::warning('Game save sync aborted: GameJolt account record not found.', [
                'gamejolt_user_id' => $gamejoltUserId,
            ]);

            return;
        }

        Log::info('Fetching game save datastore keys.', [
            'gamejolt_user_id' => $gamejoltUserId,
            'gamejolt_username' => $gamejoltAccount->username,
        ]);

        $gameSaveModel = new GameSave;
        // Laravel 12 multi-schema inspection changes apply to getTables, getViews, getTypes,
        // and getTableListing only—not getColumnListing. Use the model connection so listing
        // matches where GameSave rows are read/written below.
        $columns = $gameSaveModel->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($gameSaveModel->getTable());
        $result = [];
        $removeColumns = ['uuid', 'created_at', 'updated_at', 'user_id'];
        $columnsToFetch = array_values(array_filter(
            $columns,
            fn (string $column): bool => ! in_array($column, $removeColumns, true)
        ));
        $skippedColumns = [];

        Log::debug('Resolved game_saves columns for sync.', [
            'column_count' => count($columnsToFetch),
            'columns' => $columnsToFetch,
        ]);

        foreach ($columnsToFetch as $column) {
            $datastoreKey = 'saveStorageV1|'.$gamejoltUserId.'|'.$column;
            $dsResult = $dataStore->fetch($datastoreKey, $gamejoltAccount->username, $gamejoltAccount->token);
            $response = $dsResult['response'] ?? [];

            if ($this->isSuccessfulResponse($response)) {
                $data = $response['data'] ?? null;
                $result[$column] = $data;

                Log::debug('Fetched game save datastore key.', [
                    'column' => $column,
                    'datastore_key' => $datastoreKey,
                    'data_type' => get_debug_type($data),
                    'data_length' => is_string($data) ? strlen($data) : null,
                ]);

                continue;
            }

            if ($this->isMissingKeyResponse($response)) {
                $skippedColumns[] = $column;

                Log::debug('Game save datastore key missing; skipping column.', [
                    'column' => $column,
                    'datastore_key' => $datastoreKey,
                    'api_message' => $response['message'] ?? null,
                ]);

                continue;
            }

            $apiMessage = isset($response['message']) && is_string($response['message'])
                ? $response['message']
                : null;

            Log::warning('Game save datastore fetch failed with a hard error.', [
                'column' => $column,
                'datastore_key' => $datastoreKey,
                'api_success' => $response['success'] ?? null,
                'api_message' => $apiMessage,
                'fetched_columns' => array_keys($result),
                'fetched_column_count' => count($result),
                'skipped_columns' => $skippedColumns,
            ]);

            throw new GameJoltDataStoreFetchException(
                "GameJolt datastore fetch failed for column [{$column}].",
                $column,
                $datastoreKey,
                $apiMessage,
            );
        }

        if ($result === []) {
            Log::warning('Game save sync produced no column data; skipping database write.', [
                'gamejolt_user_id' => $gamejoltUserId,
                'skipped_columns' => $skippedColumns,
            ]);

            return;
        }

        $gameSave = GameSave::where(['user_id' => $gamejoltAccount->user_id])->first();

        if ($gameSave) {
            $gameSave->update($result);
            $gameSave->touch();

            Log::info('Updated existing game save.', [
                'game_save_uuid' => $gameSave->uuid,
                'user_id' => $gamejoltAccount->user_id,
                'synced_columns' => array_keys($result),
                'synced_column_count' => count($result),
                'skipped_columns' => $skippedColumns,
            ]);
        } else {
            $payload = $this->payloadForCreate($result, $gamejoltAccount->user_id, $columnsToFetch);
            $gameSave = GameSave::create($payload);

            Log::info('Created new game save.', [
                'game_save_uuid' => $gameSave->uuid,
                'user_id' => $gamejoltAccount->user_id,
                'synced_columns' => array_keys($result),
                'synced_column_count' => count($result),
                'skipped_columns' => $skippedColumns,
            ]);
        }

        Log::info('Finished game save sync.');
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('Game save sync job failed.', [
            'job' => self::class,
            'user_id' => $this->user->id,
            'exception' => $exception?->getMessage(),
            'exception_class' => $exception ? $exception::class : null,
        ]);
    }

    /**
     * @param  array<string, mixed>  $response
     */
    private function isSuccessfulResponse(array $response): bool
    {
        return filter_var($response['success'] ?? false, FILTER_VALIDATE_BOOLEAN) !== false;
    }

    /**
     * @param  array<string, mixed>  $response
     */
    private function isMissingKeyResponse(array $response): bool
    {
        if ($this->isSuccessfulResponse($response)) {
            return false;
        }

        $message = strtolower((string) ($response['message'] ?? ''));

        return str_contains($message, 'no item with that key could be found');
    }

    /**
     * @param  array<string, mixed>  $result
     * @param  array<int, string>  $columnsToFetch
     * @return array<string, mixed>
     */
    private function payloadForCreate(array $result, int $userId, array $columnsToFetch): array
    {
        $payload = ['user_id' => $userId];

        foreach ($columnsToFetch as $column) {
            $payload[$column] = $result[$column] ?? '';
        }

        return $payload;
    }
}
