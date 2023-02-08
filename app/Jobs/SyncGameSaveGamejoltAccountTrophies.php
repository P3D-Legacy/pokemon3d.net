<?php

namespace App\Jobs;

use App\Models\GamejoltAccount;
use App\Models\GamejoltAccountTrophy;
use App\Models\User;
use Harrk\GameJoltApi\GamejoltApi;
use Harrk\GameJoltApi\GamejoltConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SyncGameSaveGamejoltAccountTrophies implements ShouldQueue
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
     *
     * @return void
     */
    public function handle()
    {
        // Set up the Gamejolt API
        $game_id = config('services.gamejolt.game_id');
        $private_key = config('services.gamejolt.private_key');
        $api = new GamejoltApi(new GamejoltConfig($game_id, $private_key));

        // First sync all the trophies for the user with the Gamejolt API
        $gamejolt_user_id = $this->user->gamejolt->id;
        $gja = GamejoltAccount::firstWhere('id', $gamejolt_user_id);
        $trophies = $api->trophies()->fetch($gja->username, $gja->token);
        $success = $trophies['response']['success'];
        if (filter_var($success, FILTER_VALIDATE_BOOLEAN)) {
            $trophies = $trophies['response']['trophies'];
            $trophies = collect($trophies);
            foreach ($trophies as $trophy) {
                GamejoltAccountTrophy::updateOrCreate(
                    [
                        'gamejolt_account_id' => $gamejolt_user_id,
                        'id' => $trophy['id'],
                    ],
                    [
                        'title' => $trophy['title'],
                        'difficulty' => $trophy['difficulty'],
                        'description' => $trophy['description'],
                        'image_url' => $trophy['image_url'],
                    ]
                );
            }
        }
        // Then sync all the trophies for the user with the gamesave achievements
        $game_save = $this->user->gamesave;
        if ($game_save) {
            $game_save_achievements = $game_save->getAchievements();

            foreach ($game_save_achievements as $game_save_achievement) {
                if ($game_save_achievement == 'unodostres') {
                    $achievement_name = 'UnoDosTres';
                } elseif ($game_save_achievement == 'pokedex') {
                    $achievement_name = 'Pokédex';
                } else {
                    $achievement_name = Str::headline($game_save_achievement);
                }
                $trophy = $trophies->firstWhere('title', $achievement_name);
                if ($trophy) {
                    $trophy->achieved = true; // Set the trophy to achieved
                    $trophy->save();
                }
            }
        }
    }
}
