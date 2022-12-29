<?php

namespace App\Console\Commands;

use App\Models\GamejoltAccount;
use App\Models\GameSave;
use Harrk\GameJoltApi\Exceptions\TimeOutException;
use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;
use Illuminate\Console\Command;
use Schema;

class SyncGameSave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:gamesave {gamejolt_user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync a game save from the GameJolt API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $game_id = config('services.gamejolt.game_id');
        $private_key = config('services.gamejolt.private_key');
        if (!$game_id || !$private_key) {
            $this->error('Game ID or private key not set.');
            return Command::FAILURE;
        }
        $api = new GamejoltApi(new GamejoltConfig($game_id, $private_key));
        $gamejolt_user_id = $this->argument('gamejolt_user_id');
        if (!is_numeric($gamejolt_user_id) || $gamejolt_user_id < 1) {
            $this->error('GameJolt user ID must be numeric.');
            return Command::FAILURE;
        }
        $gja = GamejoltAccount::firstWhere('id', $gamejolt_user_id);
        $new_game_save = new GameSave;
        $columns = Schema::getColumnListing($new_game_save->getTable());
        $result = [];
        try {
            foreach ($columns as $column) {
                if ($column == 'uuid' or $column == 'created_at' or $column == 'updated_at' or $column == 'user_id') {
                    continue;
                }
                $key = 'saveStorageV1|'.$gamejolt_user_id.'|'.$column;
                $this->info('Getting "' . $key . '" from datastore');
                $ds_result = $api->dataStore()->fetch($key, $gja->username, $gja->token);
                $result[$column] = $ds_result['response']['data'];
            }
        } catch (TimeOutException $e) {
            $this->error('Error: '.$e->getMessage());
            return Command::FAILURE;
        }
        $game_save = GameSave::where(['user_id' => $gja->user_id])->first();
        if($game_save) {
            $game_save->update($result);
        } else {
            $result['user_id'] = $gja->user_id;
            GameSave::create($result);
        }
        $this->info('Done.');
        return Command::SUCCESS;
    }
}
