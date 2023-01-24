<?php

namespace App\Console\Commands;

use App\Models\GamejoltAccount;
use App\Models\GameSave;
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

    private function handleUser($gamejolt_user_id): void
    {
        $gja = GamejoltAccount::firstWhere('id', $gamejolt_user_id);
        $trophies = $gja->trophies;

        $gamesave = GameSave::firstWhere('user_id', $gja->user_id);
        if (! $gamesave) {
            $this->error('Game save not found for user ID '.$gja->user_id);

            return;
        }
        $game_save_achievements = $gamesave->getAchievements();

        foreach ($game_save_achievements as $game_save_achievement) {
            $achievement_name = Str::ucfirst($game_save_achievement);
            $trophy = $trophies->firstWhere('title', $achievement_name);
            if ($trophy) {
                // Set the trophy to achieved
                $trophy->achieved = true;
                $trophy->save();
                $this->info('Trophy "'.$achievement_name.'" has been updated for user ID '.$gja->user_id);
            } else {
                $this->warn('Trophy "'.$achievement_name.'" not found for user ID '.$gja->user_id);
            }
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $gamejolt_user_id = $this->argument('gamejolt_user_id');
        if ($gamejolt_user_id != 'all') {
            if (! is_numeric($gamejolt_user_id) || $gamejolt_user_id < 1) {
                $this->error('GameJolt user ID must be numeric.');

                return Command::FAILURE;
            }
        }

        if ($gamejolt_user_id == 'all') {
            $gamejolt_accounts = GamejoltAccount::all();
            foreach ($gamejolt_accounts as $gamejolt_account) {
                $this->handleUser($gamejolt_account->id);
            }
        } else {
            $this->handleUser($gamejolt_user_id);
        }

        return Command::SUCCESS;
    }
}
