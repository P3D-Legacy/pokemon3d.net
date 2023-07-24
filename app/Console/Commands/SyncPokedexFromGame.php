<?php

namespace App\Console\Commands;

use App\Models\Pokedex;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class SyncPokedexFromGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:pokedexfromgame';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync pokedex from game';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Syncing pokedex from game...");
        $url = 'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Data/pokedex.dat';
        $response = Http::get($url);
        if ($response->failed()) {
            $this->error("Failed to fetch pokedex from game!");
            return Command::FAILURE;
        }
        // Get response as text
        $body = $response->body();
        // Loop for each line in the response body
        $lines = explode("\n", $body);
        foreach ($lines as $line) {
            $pokemon_ids = array();
            // Name from first split on pipe
            $split = explode("|", $line);
            $name = trim($split[0]);
            $slug = trim($split[1]);
            $exploded_pokemon_ids = $split[2];
            $this->info("Exploded pokemon ids: " . $exploded_pokemon_ids);
            // Parse pokemon ids
            $exploded_pokemon_ids = explode(",", $exploded_pokemon_ids);
            // Loop for each pokemon id
            foreach ($exploded_pokemon_ids as $pokemon_id) {
                // Check if it contains a dash
                $start_count = 0;
                $end_count = 0;
                if (str_contains($pokemon_id, "-")) {
                    // Split on dash
                    $split = explode("-", $pokemon_id);
                    $start_count = (int)$split[0];
                    $end_count = $split[1];
                    if (str_contains($end_count, "MAX")) {
                        $end_count = 905;
                    }

                    // Check if start count is greater than end count
                    if ($start_count > $end_count) {
                        $this->error("Start count is greater than end count!");
                        return Command::FAILURE;
                    }

                    // Loop from start to end and stop at end
                    for ($i = $start_count; $i <= $end_count; $i++) {
                        // Add to pokemon ids as string in array
                        $pokemon_ids[] = (string)$i;
                    }
                } elseif (str_contains($pokemon_id, ";")) {
                    $split = explode(";", $pokemon_id);
                    $pokemon_ids[] = $split[0];
                } else {
                    // Add to pokemon ids as string in array
                    $pokemon_ids[] = $pokemon_id;
                }
            }
            $this->info("Name: " . $name);
            $this->info("Slug: " . $slug);
            $this->info("Pokemon IDs: " . implode(",", $pokemon_ids));
            // Convert pokemon ids array to json
            $pokemon_ids = json_encode($pokemon_ids);
            // Create or update pokedex
            Pokedex::updateOrCreate(
                [
                    'slug' => $slug,
                ],
                [
                    'name' => $name,
                    'pokemon_ids' => $pokemon_ids
                ]
            );
        }
        $this->info("All done!");
        return Command::SUCCESS;
    }
}
