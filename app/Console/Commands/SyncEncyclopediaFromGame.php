<?php

namespace App\Console\Commands;

use App\Models\PokemonAbility;
use App\Models\PokemonItem;
use App\Models\PokemonMove;
use App\Models\PokemonSpecies;
use App\Support\WikiSlug;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncEncyclopediaFromGame extends Command
{
    public const POKEMON_DAT_URL = 'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Pokemon/Data/{id}.dat';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:encyclopedia {--limit= : Max Pokémon data files to fetch} {--skip-dat : Skip remote Pokémon data files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync wiki encyclopedia entries from game catalogues and data files';

    public function handle(): int
    {
        $this->syncCatalogue(PokemonAbility::class, lang_path('abilities_en.json'), 'ability');
        $this->syncCatalogue(PokemonItem::class, lang_path('items_en.json'), 'item');

        $pokemon = $this->catalogue(lang_path('pokemon_en.json'));
        $limit = $this->option('limit') !== null ? (int) $this->option('limit') : count($pokemon);
        $fetched = 0;

        foreach ($pokemon as $entry) {
            $gameId = (string) ($entry['id'] ?? '');
            $name = (string) ($entry['name'] ?? '');

            if ($gameId === '' || $name === '') {
                continue;
            }

            $attributes = [
                'name' => $name,
                'slug' => $this->slugFor($name, PokemonSpecies::class, ['game_id' => $gameId]),
            ];

            if (! $this->option('skip-dat') && $fetched < $limit && ctype_digit($gameId)) {
                $data = $this->fetchPokemonDat($gameId);

                if ($data !== []) {
                    $attributes = array_merge($attributes, $data);
                    $fetched++;
                }
            }

            $species = PokemonSpecies::query()->updateOrCreate(
                ['game_id' => $gameId],
                $attributes
            );

            if ($species->wasRecentlyCreated) {
                $species->recordRevision(null, __('Synced from game data'));
            }

            $this->syncMovesFromSpecies($species);
        }

        $this->info('Encyclopedia sync complete.');

        return self::SUCCESS;
    }

    /**
     * @param  class-string  $class
     */
    protected function syncCatalogue(string $class, string $path, string $label): void
    {
        foreach ($this->catalogue($path) as $entry) {
            $gameId = (string) ($entry['id'] ?? '');
            $name = (string) ($entry['name'] ?? '');

            if ($gameId === '' || $name === '' || $name === 'None') {
                continue;
            }

            $model = $class::query()->updateOrCreate(
                ['game_id' => $gameId],
                [
                    'name' => $name,
                    'slug' => $this->slugFor($name, $class, ['game_id' => $gameId]),
                ]
            );

            if ($model->wasRecentlyCreated) {
                $model->recordRevision(null, __('Synced from game data'));
            }
        }

        $this->info("Synced {$label} catalogue.");
    }

    /**
     * @return list<array{id?: string, name?: string}>
     */
    protected function catalogue(string $path): array
    {
        if (! is_file($path)) {
            $this->warn("Catalogue missing: {$path}");

            return [];
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @param  class-string  $class
     * @param  array<string, mixed>  $identity
     */
    protected function slugFor(string $name, string $class, array $identity): string
    {
        $existing = $class::query()->where($identity)->first();

        if ($existing?->slug) {
            return $existing->slug;
        }

        return WikiSlug::unique($name, $existing);
    }

    /**
     * @return array<string, mixed>
     */
    public function fetchPokemonDat(string $gameId): array
    {
        $url = str_replace('{id}', $gameId, self::POKEMON_DAT_URL);
        $response = Http::timeout(10)->get($url);

        if ($response->failed()) {
            $this->warn("Could not fetch Pokémon data for {$gameId}.");

            return [];
        }

        return $this->parsePokemonDat($response->body());
    }

    /**
     * @return array<string, mixed>
     */
    public function parsePokemonDat(string $body): array
    {
        $fields = [];

        foreach (preg_split("/\r\n|\n|\r/", $body) ?: [] as $line) {
            if (! str_contains($line, '|')) {
                continue;
            }

            [$key, $value] = explode('|', $line, 2);
            $fields[trim($key)] = trim($value);
        }

        $pokedex = explode('\\', (string) ($fields['Pokedex'] ?? ''));
        $evolution = explode(',', (string) ($fields['EvolutionCondition'] ?? ''));
        $levelMoves = [];

        foreach ($fields as $key => $value) {
            if ($key !== 'Move') {
                continue;
            }

            [$level, $moveId] = array_pad(explode(',', $value, 2), 2, null);
            $levelMoves[] = [
                'level' => $level,
                'move_id' => $moveId,
            ];
        }

        return [
            'type_1' => $fields['Type1'] ?: null,
            'type_2' => $fields['Type2'] ?: null,
            'hp' => $this->nullableInt($fields['BaseHP'] ?? null),
            'attack' => $this->nullableInt($fields['BaseAttack'] ?? null),
            'defense' => $this->nullableInt($fields['BaseDefense'] ?? null),
            'special_attack' => $this->nullableInt($fields['BaseSpAttack'] ?? null),
            'special_defense' => $this->nullableInt($fields['BaseSpDefense'] ?? null),
            'speed' => $this->nullableInt($fields['BaseSpeed'] ?? null),
            'ability_1' => $fields['Ability1'] ?? null,
            'ability_2' => $fields['Ability2'] ?? null,
            'hidden_ability' => $fields['HiddenAbility'] ?? null,
            'pokedex_entry' => $pokedex[0] ?? null,
            'species' => $pokedex[1] ?? null,
            'weight' => isset($pokedex[2]) ? (float) $pokedex[2] : null,
            'height' => isset($pokedex[3]) ? (float) $pokedex[3] : null,
            'moves' => $levelMoves,
            'egg_moves' => $this->idList($fields['EggMoves'] ?? null),
            'machines' => $this->idList($fields['Machines'] ?? null),
            'evolution' => filled($fields['EvolutionCondition'] ?? null)
                ? [
                    'evolves_to' => $evolution[0] ?? null,
                    'method' => $evolution[1] ?? null,
                    'value' => $evolution[2] ?? null,
                ]
                : null,
        ];
    }

    protected function syncMovesFromSpecies(PokemonSpecies $species): void
    {
        $ids = collect($species->moves ?? [])
            ->pluck('move_id')
            ->merge($species->egg_moves ?? [])
            ->merge($species->machines ?? [])
            ->filter()
            ->unique()
            ->values();

        foreach ($ids as $moveId) {
            PokemonMove::query()->firstOrCreate(
                ['game_id' => (string) $moveId],
                [
                    'name' => 'Move '.$moveId,
                    'slug' => WikiSlug::unique('move-'.$moveId),
                ]
            );
        }
    }

    protected function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    /**
     * @return list<string>
     */
    protected function idList(?string $value): array
    {
        if (! filled($value)) {
            return [];
        }

        return collect(explode(',', $value))
            ->map(fn (string $id): string => trim($id))
            ->filter()
            ->values()
            ->all();
    }
}
