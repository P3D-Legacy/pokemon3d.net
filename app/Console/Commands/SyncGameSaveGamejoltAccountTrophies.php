<?php

namespace App\Console\Commands;

use App\Models\GamejoltAccount;
use App\Models\GameSave;
use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SyncGameSaveGamejoltAccountTrophies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:game-save-gamejolt-account-trophies {gamejolt_user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the game save achievements with gamejolt account trophies';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $game_id = config('services.gamejolt.game_id');
        $private_key = config('services.gamejolt.private_key');
        if (! $game_id || ! $private_key) {
            $this->error('Game ID or private key not set.');

            return Command::FAILURE;
        }
        $api = new GamejoltApi(new GamejoltConfig($game_id, $private_key));
        $gamejolt_user_id = $this->argument('gamejolt_user_id');
        if (! is_numeric($gamejolt_user_id) || $gamejolt_user_id < 1) {
            $this->error('GameJolt user ID must be numeric.');

            return Command::FAILURE;
        }

        $gja = GamejoltAccount::firstWhere('id', $gamejolt_user_id);
        $trophies = $gja->trophies;

        $game_save_achievements = GameSave::firstWhere('user_id', $gja->user_id)->getAchievements();

        foreach ($game_save_achievements as $game_save_achievement) {
            $achievement_name = Str::ucfirst($game_save_achievement);
            $this->info('Checking if "'.$achievement_name.'" is in the trophies list');
            $trophy = $trophies->firstWhere('title', $achievement_name);
            if ($trophy) {
                # Set the trophy to achieved
                $trophy->achieved = true;
                $trophy->save();
                $this->info('Trophy "'.$achievement_name.'" has been updated.');
            } else {
                $this->info('Trophy "'.$achievement_name.'" not found.');
            }
        }

        return Command::SUCCESS;
    }
}
