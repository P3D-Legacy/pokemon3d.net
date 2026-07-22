<?php

namespace App\Console\Commands;

use App\Models\Pokedex;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncPokedexFromGame extends Command
{
    public const NATIONAL_MAX = 905;

    public const POKEDEX_DAT_URL = 'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Data/pokedex.dat';

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
    protected $description = 'Sync Pokédex definitions from the game data file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Syncing Pokédex definitions from game...');

        $response = Http::get(self::POKEDEX_DAT_URL);

        if ($response->failed()) {
            $this->error('Failed to fetch pokedex.dat from the game repository.');

            return self::FAILURE;
        }

        $lines = preg_split("/\r\n|\n|\r/", $response->body()) ?: [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $parts = explode('|', $line);

            if (count($parts) < 3) {
                $this->warn("Skipping malformed line: {$line}");

                continue;
            }

            $name = trim($parts[0]);
            $slug = trim($parts[1]);
            $pokemonIds = $this->expandPokemonIds(trim($parts[2]));

            if ($slug === '') {
                $this->warn("Skipping Pokédex without slug: {$name}");

                continue;
            }

            Pokedex::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'pokemon_ids' => $pokemonIds,
                ]
            );

            $this->info("Synced {$name} ({$slug}) with ".count($pokemonIds).' entries.');
        }

        $this->info('All done!');

        return self::SUCCESS;
    }

    /**
     * @return list<string>
     */
    public function expandPokemonIds(string $idList): array
    {
        $pokemonIds = [];

        foreach (explode(',', $idList) as $token) {
            $token = trim($token);

            if ($token === '') {
                continue;
            }

            if (str_contains($token, '-')) {
                [$start, $end] = explode('-', $token, 2);
                $startCount = (int) $start;
                $endCount = str_contains($end, 'MAX') ? self::NATIONAL_MAX : (int) $end;

                if ($startCount > $endCount) {
                    continue;
                }

                for ($i = $startCount; $i <= $endCount; $i++) {
                    $pokemonIds[] = (string) $i;
                }

                continue;
            }

            $pokemonIds[] = $token;
        }

        return $pokemonIds;
    }
}
