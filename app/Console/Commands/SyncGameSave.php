<?php

namespace App\Console\Commands;

use App\Jobs\SyncGameSaveForUser;
use App\Models\GamejoltAccount;
use Illuminate\Console\Command;

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
     */
    public function handle(): int
    {
        $game_id = config('services.gamejolt.game_id');
        $private_key = config('services.gamejolt.private_key');
        if (! $game_id || ! $private_key) {
            $this->error('Game ID or private key not set.');

            return Command::FAILURE;
        }
        $gamejolt_user_id = $this->argument('gamejolt_user_id');
        if ($gamejolt_user_id != 'all') {
            if (! is_numeric($gamejolt_user_id) || $gamejolt_user_id < 1) {
                $this->error('GameJolt user ID must be numeric.');

                return Command::FAILURE;
            }
        }

        if ($gamejolt_user_id == 'all') {
            $gamejolt_accounts = GamejoltAccount::all();
            $this->info("Dispatching game save sync for {$gamejolt_accounts->count()} GameJolt account(s).");

            foreach ($gamejolt_accounts as $gamejolt_account) {
                SyncGameSaveForUser::dispatch($gamejolt_account->user);
                $this->line("Queued sync for GameJolt user {$gamejolt_account->id} (user_id: {$gamejolt_account->user_id}).");
            }
        } else {
            $gamejolt_account = GamejoltAccount::firstWhere('id', $gamejolt_user_id);

            if (! $gamejolt_account) {
                $this->error("GameJolt account [{$gamejolt_user_id}] not found.");

                return Command::FAILURE;
            }

            SyncGameSaveForUser::dispatch($gamejolt_account->user);
            $this->info("Queued sync for GameJolt user {$gamejolt_account->id} (user_id: {$gamejolt_account->user_id}).");
        }

        $this->info('Done.');

        return Command::SUCCESS;
    }
}
