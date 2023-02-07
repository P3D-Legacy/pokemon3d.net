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
use Schema;

class SyncGameSaveForUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     *
     * @return void
     */
    public function handle()
    {
        
        $api = new GamejoltApi(new GamejoltConfig(config('services.gamejolt.game_id'), config('services.gamejolt.private_key')));
        $gamejolt_user_id = $this->user->gamejolt_account->id;
        $gja = GamejoltAccount::firstWhere('id', $gamejolt_user_id);
        $gamesave_model = new GameSave;
        $columns = Schema::getColumnListing($gamesave_model->getTable());
        $result = [];
        $remove_columns = ['uuid', 'created_at', 'updated_at', 'user_id'];
        foreach ($columns as $column) {
            if (in_array($column, $remove_columns)) {
                continue;
            }
            $datastore_key = 'saveStorageV1|'.$gamejolt_user_id.'|'.$column;
            $ds_result = $api->dataStore()->fetch($datastore_key, $gja->username, $gja->token);
            $success = $ds_result['response']['success'];
            if (filter_var($success, FILTER_VALIDATE_BOOLEAN)) {
                $result[$column] = $ds_result['response']['data'];
            } else {
                break;
            }
        }
        $game_save = GameSave::where(['user_id' => $gja->user_id])->first();
        if ($game_save) {
            $game_save->update($result);
            $game_save->touch(); // Update updated_at
        } else {
            $result['user_id'] = $gja->user_id;
            GameSave::create($result);
        }
    }
}
