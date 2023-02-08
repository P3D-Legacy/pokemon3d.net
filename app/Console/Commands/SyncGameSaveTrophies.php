<?php

namespace App\Console\Commands;

use App\Jobs\SyncGameSaveGamejoltAccountTrophies;
use App\Models\User;
use Illuminate\Console\Command;

class SyncGameSaveTrophies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:gamesavetrophies {user_id=all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync trophies from the GameJolt API with the users game save';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $argument = $this->argument('user_id');
        if ($argument != 'all') {
            if (! is_numeric($argument) || $argument < 1) {
                $this->error('User ID must be numeric');

                return Command::FAILURE;
            }
        }

        if ($argument == 'all') {
            $users = User::all();
            foreach ($users as $user) {
                SyncGameSaveGamejoltAccountTrophies::dispatch($user);
            }
        } else {
            $user = User::find($argument);
            if (! $user) {
                $this->error('User not found');

                return Command::FAILURE;
            }
            SyncGameSaveGamejoltAccountTrophies::dispatch($user);
        }

        return Command::SUCCESS;
    }
}
