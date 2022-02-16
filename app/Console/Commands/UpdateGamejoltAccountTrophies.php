<?php

namespace App\Console\Commands;

use App\Http\Livewire\Login\GameJolt;
use App\Models\GamejoltAccount;
use Illuminate\Console\Command;
use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;
use Harrk\GameJoltApi\Exceptions\TimeOutException;

class UpdateGamejoltAccountTrophies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gj:update-trophies';

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $api = new GamejoltApi(new GamejoltConfig(env('GAMEJOLT_GAME_ID'), env('GAMEJOLT_GAME_PRIVATE_KEY')));
        $accounts = GamejoltAccount::all();
        foreach ($accounts as $account) {
            try {
                $trophies = $api->trophies()->fetch($account->username, $account->token);
                if (filter_var($trophies['response']['success'], FILTER_VALIDATE_BOOLEAN) === false) {
                    $this->error("No success for {$account->username}");
                    return;
                }
                $trophies = $trophies['response']['trophies'];
                $trophy_count = count($trophies);
                $this->info("Found {$trophy_count} for {$account->username}");
                foreach ($trophies as $trophy) {
                    $account->trophies()->updateOrCreate(
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
        $this->info('Done.');
        return Command::SUCCESS;
    }
}
