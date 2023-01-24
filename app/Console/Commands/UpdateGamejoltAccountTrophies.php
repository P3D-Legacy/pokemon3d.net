<?php

namespace App\Console\Commands;

use App\Models\GamejoltAccount;
use App\Models\GamejoltAccountTrophy;
use Harrk\GameJoltApi\Exceptions\TimeOutException;
use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;
use Illuminate\Console\Command;

class UpdateGamejoltAccountTrophies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gj:update-trophies {gamejolt_user_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the trophies for all users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function handleUser($gamejolt_user_id, $api): void
    {
        try {
            $account = GamejoltAccount::firstWhere('id', $gamejolt_user_id);
            $trophies = $api->trophies()->fetch($account->username, $account->token);
            if (filter_var($trophies['response']['success'], FILTER_VALIDATE_BOOLEAN) === false) {
                $this->error("No success for {$account->username}");

                return;
            }
            $trophies = $trophies['response']['trophies'];
            $trophy_count = count($trophies);
            $this->info("Found {$trophy_count} for {$account->username}");
            foreach ($trophies as $trophy) {
                GamejoltAccountTrophy::updateOrCreate(
                    [
                        'gamejolt_account_id' => $account->id,
                        'id' => $trophy['id'],
                    ],
                    [
                        'title' => $trophy['title'],
                        'difficulty' => $trophy['difficulty'],
                        'description' => $trophy['description'],
                        'image_url' => $trophy['image_url'],
                        'achieved' => filter_var($trophy['achieved'], FILTER_VALIDATE_BOOLEAN),
                    ]
                );
            }
            $this->info("Sync done for {$account->username}");
        } catch (TimeOutException $e) {
            $this->error("Timeout for {$account->username}");
        } catch (\Exception $e) {
            $this->error("Unknown Error: {$e->getMessage()}");
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $gamejolt_user_id = $this->argument('gamejolt_user_id');
        if ($gamejolt_user_id != 'all') {
            if (! is_numeric($gamejolt_user_id) || $gamejolt_user_id < 1) {
                $this->error('GameJolt user ID must be numeric.');

                return Command::FAILURE;
            }
        }

        $api = new GamejoltApi(new GamejoltConfig(env('GAMEJOLT_GAME_ID'), env('GAMEJOLT_GAME_PRIVATE_KEY')));

        if ($gamejolt_user_id == 'all') {
            $gamejolt_accounts = GamejoltAccount::all();
            foreach ($gamejolt_accounts as $gamejolt_account) {
                $this->handleUser($gamejolt_account->id, $api);
            }
        } else {
            $this->handleUser($gamejolt_user_id, $api);
        }

        $this->info('Done.');

        return 0;
    }
}
