<?php

namespace App\Jobs;

use App\Models\GamejoltAccount;
use App\Models\GameSave;
use App\Models\User;
use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncGameSaveForUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::withContext([
            'job' => self::class,
            'user_id' => $this->user->id,
        ]);

        Log::info('Starting game save sync.');

        $game_id = config('services.gamejolt.game_id');
        $private_key = config('services.gamejolt.private_key');

        if (! $game_id || ! $private_key) {
            Log::warning('Game save sync aborted: GameJolt credentials are not configured.', [
                'has_game_id' => filled($game_id),
                'has_private_key' => filled($private_key),
            ]);

            return;
        }

        $this->user->loadMissing('gamejolt');

        if (! $this->user->gamejolt) {
            Log::warning('Game save sync aborted: user has no linked GameJolt account.');

            return;
        }

        $api = new GamejoltApi(new GamejoltConfig($game_id, $private_key));
        $gamejolt_user_id = $this->user->gamejolt->id;
        $gja = GamejoltAccount::firstWhere('id', $gamejolt_user_id);

        if (! $gja) {
            Log::warning('Game save sync aborted: GameJolt account record not found.', [
                'gamejolt_user_id' => $gamejolt_user_id,
            ]);

            return;
        }

        Log::info('Fetching game save datastore keys.', [
            'gamejolt_user_id' => $gamejolt_user_id,
            'gamejolt_username' => $gja->username,
        ]);

        $gamesave_model = new GameSave;
        // Laravel 12 multi-schema inspection changes apply to getTables, getViews, getTypes,
        // and getTableListing only—not getColumnListing. Use the model connection so listing
        // matches where GameSave rows are read/written below.
        $columns = $gamesave_model->getConnection()
            ->getSchemaBuilder()
            ->getColumnListing($gamesave_model->getTable());
        $result = [];
        $remove_columns = ['uuid', 'created_at', 'updated_at', 'user_id'];
        $columns_to_fetch = array_values(array_filter(
            $columns,
            fn (string $column): bool => ! in_array($column, $remove_columns, true)
        ));
        $failed_column = null;
        $failed_message = null;

        Log::debug('Resolved game_saves columns for sync.', [
            'column_count' => count($columns_to_fetch),
            'columns' => $columns_to_fetch,
        ]);

        foreach ($columns_to_fetch as $column) {
            $datastore_key = 'saveStorageV1|'.$gamejolt_user_id.'|'.$column;
            $ds_result = $api->dataStore()->fetch($datastore_key, $gja->username, $gja->token);
            $response = $ds_result['response'] ?? [];
            $success = $response['success'] ?? false;

            if (filter_var($success, FILTER_VALIDATE_BOOLEAN)) {
                $data = $response['data'] ?? null;
                $result[$column] = $data;

                Log::debug('Fetched game save datastore key.', [
                    'column' => $column,
                    'datastore_key' => $datastore_key,
                    'data_type' => get_debug_type($data),
                    'data_length' => is_string($data) ? strlen($data) : null,
                ]);

                continue;
            }

            $failed_column = $column;
            $failed_message = $response['message'] ?? null;

            Log::warning('Game save datastore fetch failed; stopping further column fetches.', [
                'column' => $column,
                'datastore_key' => $datastore_key,
                'api_success' => $success,
                'api_message' => $failed_message,
                'fetched_columns' => array_keys($result),
                'fetched_column_count' => count($result),
            ]);

            break;
        }

        if ($result === []) {
            Log::warning('Game save sync produced no column data; skipping database write.', [
                'gamejolt_user_id' => $gamejolt_user_id,
                'failed_column' => $failed_column,
                'api_message' => $failed_message,
            ]);

            return;
        }

        $game_save = GameSave::where(['user_id' => $gja->user_id])->first();

        if ($game_save) {
            $game_save->update($result);
            $game_save->touch(); // Update updated_at

            Log::info('Updated existing game save.', [
                'game_save_uuid' => $game_save->uuid,
                'user_id' => $gja->user_id,
                'synced_columns' => array_keys($result),
                'synced_column_count' => count($result),
                'stopped_early' => $failed_column !== null,
                'failed_column' => $failed_column,
                'api_message' => $failed_message,
            ]);
        } else {
            $result['user_id'] = $gja->user_id;
            $game_save = GameSave::create($result);

            Log::info('Created new game save.', [
                'game_save_uuid' => $game_save->uuid,
                'user_id' => $gja->user_id,
                'synced_columns' => array_keys(array_diff_key($result, ['user_id' => true])),
                'synced_column_count' => count($result) - 1,
                'stopped_early' => $failed_column !== null,
                'failed_column' => $failed_column,
                'api_message' => $failed_message,
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
}
