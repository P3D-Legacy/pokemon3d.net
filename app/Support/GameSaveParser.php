<?php

namespace App\Support;

use App\Models\GameSave;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GameSaveParser
{
    private ?array $playerData = null;

    private ?string $playerSource = null;

    private ?array $pokedexEntries = null;

    private ?string $pokedexSource = null;

    public function __construct(
        private GameSave $save,
        private GameDataCatalogue $catalogue,
    ) {}

    public function getPlayerData($key_name = null)
    {
        try {
            $playerData = $this->parsedPlayerData();

            return $playerData[$key_name] ?? $playerData;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return [];
    }

    /**
     * @return array<string, string>
     */
    private function parsedPlayerData(): array
    {
        $source = (string) $this->save->player;

        if ($this->playerSource === $source && $this->playerData !== null) {
            return $this->playerData;
        }

        $playerDataLines = preg_split('/\r\n|\n|\r/', $source) ?: [];
        $playerData = [];

        foreach ($playerDataLines as $line) {
            if ($line === '') {
                continue;
            }

            $parts = explode('|', $line, 2);

            if (count($parts) < 2) {
                continue;
            }

            $playerData[ucfirst($parts[0])] = $parts[1];
        }

        $this->playerSource = $source;

        return $this->playerData = $playerData;
    }

    public function getPlayerDataDetails(): array
    {
        try {
            if (! $this->getPlayerData()) {
                return [];
            }
            $details = [];
            $allowed_details = ['Name', 'RivalName', 'Location', 'Money', 'HasPokedex', 'HasPokegear', 'SaveCreated', 'Gender', 'OT', 'Points', 'GTSStars'];
            foreach ($allowed_details as $detail) {
                if ($detail === 'HasPokedex' or $detail === 'HasPokegear') {
                    $details[$detail] = $this->getPlayerData($detail) === '1' ? trans('Yes') : trans('No');

                    continue;
                }
                $details[$detail] = $this->getPlayerData($detail);
            }

            return $details;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return [];
    }

    public function getAchievements(): array
    {
        try {
            $earnedAchievements = $this->getPlayerData('EarnedAchievements');

            // Verify that it is a string
            if (! is_string($earnedAchievements)) {
                Log::error('EarnedAchievements is not a string');
                Log::error($earnedAchievements);
                Log::error('User ID: '.$this->save->user_id);

                return [];
            }

            return explode(',', $earnedAchievements);
        } catch (Exception $e) {
            // If there is an error, return an empty array and log the error
            Log::error($e->getMessage());

            return [];
        }
    }

    public function getPokedex(): array
    {
        $source = (string) $this->save->pokedex;

        if ($this->pokedexSource === $source && $this->pokedexEntries !== null) {
            return $this->pokedexEntries;
        }

        $this->pokedexSource = $source;

        try {
            $pokedex = explode("\r\n", $source);
            $pokedex = array_filter($pokedex);

            return $this->pokedexEntries = array_map(function ($item) {
                $item = explode('|', $item);
                $id = str_replace(['{', '}'], '', $item[0]);
                $status = (int) $item[1];

                return [
                    'id' => $id,
                    'name' => $this->catalogue->pokemonName($id),
                    'seen' => $status >= 1,
                    'caught' => $status >= 2,
                ];
            }, $pokedex);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return $this->pokedexEntries = [];
        }
    }

    /**
     * Return Pokédex entries for the given IDs in definition order.
     * Missing save rows are returned as unseen placeholders.
     *
     * @param  list<string>  $ids
     * @return list<array{id: string, name: string, seen: bool, caught: bool}>
     */
    public function getPokedexByIds(array $ids): array
    {
        try {
            $entriesById = collect($this->getPokedex())->keyBy('id');

            return array_map(function (string $id) use ($entriesById): array {
                $entry = $entriesById->get($id);

                if (is_array($entry)) {
                    return $entry;
                }

                return [
                    'id' => $id,
                    'name' => $this->catalogue->pokemonName($id),
                    'seen' => false,
                    'caught' => false,
                ];
            }, array_values($ids));
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return [];
        }
    }

    // Get all seen pokemon in pokedex
    public function getSeenPokemon(): array
    {
        try {
            $pokedex = $this->getPokedex();

            return array_filter($pokedex, function ($item) {
                return $item['seen'];
            });
        } catch (Exception $e) {
            // If there is an error, return an empty array and log the error
            Log::error($e->getMessage());

            return [];
        }
    }

    // Get all caught pokemon in pokedex
    public function getCaughtPokemon(): array
    {
        try {
            $pokedex = $this->getPokedex();

            return array_filter($pokedex, function ($item) {
                return $item['caught'];
            });
        } catch (Exception $e) {
            // If there is an error, return an empty array and log the error
            Log::error($e->getMessage());

            return [];
        }
    }

    // Get count of seen pokemon in pokedex
    public function getSeenPokemonCount(): int
    {
        return count($this->getSeenPokemon());
    }

    // Get count of caught pokemon in pokedex
    public function getCaughtPokemonCount(): int
    {
        return count($this->getCaughtPokemon());
    }

    // Get statistics
    public function getStatistics(): array
    {
        try {
            $statistics = $this->save->statistics;
            $statistics = explode("\r\n", $statistics);
            $statistics = array_filter($statistics);

            return array_map(function ($item) {
                $item = explode(',', $item);
                $name = str_replace('{', '', $item[0]);
                // Remove [ and ] and some random number between from the name
                $name = preg_replace('/\[[0-9]+\]/', '', $name);
                // Remove the last three characters to remove the .00
                $number = substr($item[1], 0, -3);

                return [
                    'name' => $name,
                    'value' => $number,
                ];
            }, $statistics);
        } catch (Exception $e) {
            // If there is an error, return an empty array and log the error
            Log::error($e->getMessage());

            return [];
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getParty(): array
    {
        try {
            $lines = $this->splitSaveLines((string) $this->save->party);

            return array_values(array_filter(array_map(
                fn (string $line): ?array => $this->parsePokemonCode($line),
                $lines
            )));
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return [];
        }
    }

    /**
     * Parse PC storage box lines: BoxIndex,BoxPosition,PokemonCode
     *
     * @return list<array{box_index: int, position: int, pokemon: array<string, mixed>}>
     */
    public function getBox(): array
    {
        try {
            $entries = [];

            foreach ($this->splitSaveLines((string) $this->save->box) as $line) {
                $bracePos = strpos($line, '{');

                if ($bracePos === false) {
                    continue;
                }

                $prefix = rtrim(substr($line, 0, $bracePos), ',');
                $parts = explode(',', $prefix);

                if (count($parts) < 2) {
                    continue;
                }

                $pokemon = $this->parsePokemonCode(substr($line, $bracePos));

                if ($pokemon === null) {
                    continue;
                }

                $entries[] = [
                    'box_index' => (int) $parts[0],
                    'position' => (int) $parts[1],
                    'pokemon' => $pokemon,
                ];
            }

            usort($entries, function (array $a, array $b): int {
                return [$a['box_index'], $a['position']] <=> [$b['box_index'], $b['position']];
            });

            return $entries;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return [];
        }
    }

    /**
     * Parse bag inventory lines: {ItemID|Amount}
     *
     * @return list<array{id: string, name: string, amount: int}>
     */
    public function getItems(): array
    {
        try {
            $items = [];

            foreach ($this->splitSaveLines((string) $this->save->items) as $line) {
                if (str_starts_with($line, 'Mail|')) {
                    continue;
                }

                if (! str_starts_with($line, '{') || ! str_ends_with($line, '}') || ! str_contains($line, '|')) {
                    continue;
                }

                $inner = substr($line, 1, -1);
                [$itemId, $amount] = array_pad(explode('|', $inner, 2), 2, '0');

                $items[] = [
                    'id' => (string) $itemId,
                    'name' => $this->catalogue->itemName((string) $itemId),
                    'amount' => (int) $amount,
                ];
            }

            return $items;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return [];
        }
    }

    /**
     * Parse daycare lines: DayCareID,Slot,PokemonCode or DayCareID,Egg,PokemonID
     *
     * @return list<array<string, mixed>>
     */
    public function getDaycare(): array
    {
        try {
            $entries = [];
            $spriteBase = 'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Pokemon/';

            foreach ($this->splitSaveLines((string) $this->save->daycare) as $line) {
                $bracePos = strpos($line, '{');

                if ($bracePos !== false) {
                    $prefix = rtrim(substr($line, 0, $bracePos), ',');
                    $parts = explode(',', $prefix);

                    if (count($parts) < 2) {
                        continue;
                    }

                    $pokemon = $this->parsePokemonCode(substr($line, $bracePos));

                    if ($pokemon === null) {
                        continue;
                    }

                    $entries[] = [
                        'daycare_id' => (int) $parts[0],
                        'slot' => (int) $parts[1],
                        'is_egg' => false,
                        'pokemon' => $pokemon,
                    ];

                    continue;
                }

                $parts = explode(',', $line, 3);

                if (count($parts) < 3 || strcasecmp($parts[1], 'Egg') !== 0) {
                    continue;
                }

                $pokemonId = (string) $parts[2];
                $numericId = (int) explode('_', $pokemonId)[0];

                $entries[] = [
                    'daycare_id' => (int) $parts[0],
                    'slot' => 'Egg',
                    'is_egg' => true,
                    'pokemon' => [
                        'id' => $numericId,
                        'name' => $this->catalogue->pokemonName((string) $numericId),
                        'nickname' => null,
                        'level' => 1,
                        'gender' => null,
                        'nature' => null,
                        'ability' => null,
                        'friendship' => null,
                        'experience' => '0',
                        'status' => null,
                        'shiny' => false,
                        'is_egg' => true,
                        'sprite_url' => $spriteBase.'Egg/Egg_front.png',
                    ],
                ];
            }

            return $entries;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return [];
        }
    }

    /**
     * Parse Hall of Fame entries.
     *
     * @return list<array{id: int, name: ?string, play_time: ?string, points: ?string, ot: ?string, skin: ?string, pokemon: list<array<string, mixed>>}>
     */
    public function getHallOfFame(): array
    {
        try {
            /** @var array<int, array{id: int, name: ?string, play_time: ?string, points: ?string, ot: ?string, skin: ?string, pokemon: list<array<string, mixed>>}> $entries */
            $entries = [];

            foreach ($this->splitSaveLines((string) $this->save->halloffame) as $line) {
                if (! preg_match('/^(\d+),(.*)$/', $line, $matches)) {
                    continue;
                }

                $entryId = (int) $matches[1];
                $payload = $matches[2];

                if (! isset($entries[$entryId])) {
                    $entries[$entryId] = [
                        'id' => $entryId,
                        'name' => null,
                        'play_time' => null,
                        'points' => null,
                        'ot' => null,
                        'skin' => null,
                        'pokemon' => [],
                    ];
                }

                if (str_starts_with($payload, '(') && str_ends_with($payload, ')')) {
                    $playerData = explode('|', substr($payload, 1, -1));

                    if (count($playerData) >= 5) {
                        $entries[$entryId]['name'] = $playerData[0];
                        $entries[$entryId]['play_time'] = $playerData[1];
                        $entries[$entryId]['points'] = $playerData[2];
                        $entries[$entryId]['ot'] = $playerData[3];
                        $entries[$entryId]['skin'] = $playerData[4];
                    } elseif (count($playerData) >= 4) {
                        $entries[$entryId]['name'] = $playerData[0];
                        $entries[$entryId]['play_time'] = $playerData[1];
                        $entries[$entryId]['points'] = $playerData[2];
                        $entries[$entryId]['ot'] = '00000';
                        $entries[$entryId]['skin'] = $playerData[3];
                    }

                    continue;
                }

                if (str_starts_with($payload, '{')) {
                    $pokemon = $this->parsePokemonCode($payload);

                    if ($pokemon !== null) {
                        $entries[$entryId]['pokemon'][] = $pokemon;
                    }
                }
            }

            ksort($entries);

            return array_values($entries);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return [];
        }
    }

    /**
     * Parse roaming Pokémon lines.
     *
     * @return list<array{roamer_id: string, pokemon_id: string, level: int, world_id: int, level_file: string, music_loop: string, shiny: bool, pokemon: ?array<string, mixed>}>
     */
    public function getRoamingPokemon(): array
    {
        try {
            $entries = [];

            foreach ($this->splitSaveLines((string) $this->save->roamingpokemon) as $line) {
                $parts = explode('|', $line);

                if (count($parts) < 8) {
                    continue;
                }

                $pokemon = $this->parsePokemonCode($parts[7]);

                $entries[] = [
                    'roamer_id' => $parts[0],
                    'pokemon_id' => $parts[1],
                    'level' => (int) $parts[2],
                    'world_id' => (int) $parts[3],
                    'level_file' => $parts[4],
                    'music_loop' => $parts[5],
                    'shiny' => filter_var($parts[6], FILTER_VALIDATE_BOOLEAN),
                    'pokemon' => $pokemon,
                ];
            }

            return $entries;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return [];
        }
    }

    /**
     * Parse apricorn tree / Kurt lines.
     *
     * @return list<array<string, mixed>>
     */
    public function getApricorns(): array
    {
        try {
            $entries = [];

            foreach ($this->splitSaveLines((string) $this->save->apricorns) as $line) {
                if (! str_starts_with($line, '{') || ! str_ends_with($line, '}')) {
                    continue;
                }

                $inner = substr($line, 1, -1);
                $parts = explode('|', $inner);

                if (count($parts) < 2) {
                    continue;
                }

                if (strcasecmp($parts[0], 'Kurt') === 0) {
                    $amounts = array_pad(explode(',', $parts[1] ?? ''), 7, '0');
                    $entries[] = [
                        'type' => 'kurt',
                        'map_path' => null,
                        'map_name' => null,
                        'position' => null,
                        'amounts' => [
                            'red' => (int) $amounts[0],
                            'blue' => (int) $amounts[1],
                            'yellow' => (int) $amounts[2],
                            'green' => (int) $amounts[3],
                            'white' => (int) $amounts[4],
                            'black' => (int) $amounts[5],
                            'pink' => (int) $amounts[6],
                        ],
                        'timestamp' => $parts[2] ?? null,
                    ];

                    continue;
                }

                $entries[] = [
                    'type' => 'tree',
                    'map_path' => $parts[0],
                    'map_name' => MapNameCatalogue::name($parts[0]),
                    'position' => $parts[1] ?? null,
                    'amounts' => null,
                    'timestamp' => $parts[2] ?? null,
                ];
            }

            return $entries;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return [];
        }
    }

    /**
     * Parse planted berry lines.
     *
     * P3D format: {map|x,y,z|berryIndex|amount|watered|timestamp|fullGrown}
     * Berry item IDs are berryIndex + 2000.
     *
     * @return list<array{map_path: string, map_name: string, position: ?string, berry_id: string, berry_name: string, berry_count: int, watered_stages: int, timestamp: ?string}>
     */
    public function getBerries(): array
    {
        try {
            $entries = [];

            foreach ($this->splitSaveLines((string) $this->save->berries) as $line) {
                if (! str_starts_with($line, '{') || ! str_ends_with($line, '}')) {
                    continue;
                }

                $inner = substr($line, 1, -1);
                $parts = explode('|', $inner);

                if (count($parts) < 4) {
                    continue;
                }

                $berryId = (string) (((int) $parts[2]) + 2000);
                $wateredStages = count(array_filter(
                    explode(',', $parts[4] ?? '0'),
                    fn (string $flag): bool => $flag === '1'
                ));

                $entries[] = [
                    'map_path' => $parts[0],
                    'map_name' => MapNameCatalogue::name($parts[0]),
                    'position' => $parts[1] ?? null,
                    'berry_id' => $berryId,
                    'berry_name' => $this->catalogue->itemName($berryId),
                    'berry_count' => (int) $parts[3],
                    'watered_stages' => $wateredStages,
                    'timestamp' => $parts[5] ?? null,
                ];
            }

            return $entries;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return [];
        }
    }

    /**
     * Parse collected map item pickups: MapPath|ItemID (comma-separated).
     *
     * @return list<array{map_path: string, map_name: string, item_id: string, item_name: string}>
     */
    public function getItemData(): array
    {
        try {
            $raw = trim((string) $this->save->itemdata);

            if ($raw === '') {
                return [];
            }

            $entries = [];

            foreach (array_filter(explode(',', $raw)) as $chunk) {
                $chunk = trim($chunk);

                if ($chunk === '' || ! str_contains($chunk, '|')) {
                    continue;
                }

                [$mapPath, $itemId] = array_pad(explode('|', $chunk, 2), 2, '');

                if ($mapPath === '' || $itemId === '') {
                    continue;
                }

                $entries[] = [
                    'map_path' => $mapPath,
                    'map_name' => MapNameCatalogue::name($mapPath),
                    'item_id' => $itemId,
                    'item_name' => $this->catalogue->itemName($itemId),
                ];
            }

            return $entries;
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return [];
        }
    }

    /**
     * Parse a P3D Pokémon code blob into a display-friendly array.
     *
     * @return array<string, mixed>|null
     */
    public function parsePokemonCode(string $line): ?array
    {
        $line = trim($line);

        if ($line === '' || ! str_contains($line, '{')) {
            return null;
        }

        $spriteBase = 'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Pokemon/';
        $properties = explode('}{', $line);
        $raw = [];

        foreach ($properties as $property) {
            $parts = explode('"[', $property, 2);

            if (count($parts) < 2) {
                continue;
            }

            $key = str_replace(['{', '"'], '', $parts[0]);
            $value = str_replace([']', '}'], '', $parts[1]);

            if ($key === '' || $key === 'IDValue') {
                continue;
            }

            $raw[$key] = $value;
        }

        if ($raw === []) {
            return null;
        }

        $pokemonId = (int) ($raw['Pokemon'] ?? 0);
        $eggSteps = (int) ($raw['EggSteps'] ?? 0);
        $isEgg = $eggSteps > 0;
        $nickname = trim((string) ($raw['NickName'] ?? ''));
        $status = trim((string) ($raw['Status'] ?? ''));
        $experience = (string) ($raw['Experience'] ?? '0');

        if (str_ends_with($experience, '.00')) {
            $experience = substr($experience, 0, -3);
        }

        $friendshipRaw = $raw['Friendship'] ?? null;
        $friendship = $friendshipRaw !== null && $friendshipRaw !== ''
            ? round(((float) $friendshipRaw) / 255 * 100, 0).'%'
            : null;

        $shiny = filter_var($raw['isShiny'] ?? false, FILTER_VALIDATE_BOOLEAN);

        return [
            'id' => $pokemonId,
            'name' => $this->catalogue->pokemonName((string) $pokemonId),
            'nickname' => $nickname !== '' ? $nickname : null,
            'level' => (int) ($raw['Level'] ?? 0),
            'gender' => $this->getPartyGenderLabel($raw['Gender'] ?? null),
            'nature' => ! $isEgg && isset($raw['Nature']) ? $this->getNature($raw['Nature']) : null,
            'ability' => ! $isEgg && isset($raw['Ability']) ? $this->getAbility($raw['Ability'], $pokemonId) : null,
            'friendship' => $friendship,
            'experience' => $experience,
            'status' => $status !== '' ? $status : null,
            'shiny' => $shiny,
            'is_egg' => $isEgg,
            'sprite_url' => $isEgg
                ? $spriteBase.'Egg/Egg_front.png'
                : $spriteBase.'Sprites/'.$pokemonId.'.png',
        ];
    }

    /**
     * @return list<string>
     */
    private function splitSaveLines(string $raw): array
    {
        return array_values(array_filter(
            preg_split('/\r\n|\n|\r/', $raw) ?: [],
            fn (string $line): bool => $line !== ''
        ));
    }

    private function getPartyGenderLabel(mixed $gender): ?string
    {
        if ($gender === null || $gender === '') {
            return null;
        }

        return match ((int) $gender) {
            0 => __('Male'),
            1 => __('Female'),
            2 => __('Genderless'),
            default => null,
        };
    }

    // Get nature from int
    public function getNature(mixed $natureInt): string
    {
        return $this->catalogue->natureName((string) $natureInt) ?? '???';
    }

    /**
     * Resolve an ability ID or slot letter (A/B/C/H from game 0.61+) to a name.
     */
    public function getAbility(mixed $ability, ?int $pokemonId = null): string
    {
        if ($ability === null || $ability === '') {
            return '???';
        }

        $ability = trim((string) $ability);

        if (preg_match('/^[ABCH]$/i', $ability) === 1) {
            $resolvedId = $this->resolveAbilityIdFromSlot($pokemonId, strtoupper($ability));

            if ($resolvedId === null) {
                return strtoupper($ability);
            }

            $ability = (string) $resolvedId;
        }

        if (! is_numeric($ability)) {
            return $ability;
        }

        return $this->catalogue->abilityName((string) $ability) ?? '???';
    }

    /**
     * Map game ability slots (A/B/C/H) to ability IDs from the species data file.
     */
    public function resolveAbilityIdFromSlot(?int $pokemonId, string $slot): ?int
    {
        if ($pokemonId === null || $pokemonId <= 0) {
            return null;
        }

        $slots = $this->getPokemonAbilitySlots($pokemonId);
        $abilityValue = $slots[$slot] ?? null;

        if ($abilityValue === null || $abilityValue === '' || strcasecmp($abilityValue, 'Nothing') === 0) {
            return null;
        }

        if (! is_numeric($abilityValue)) {
            return null;
        }

        return (int) $abilityValue;
    }

    /**
     * @return array{A: ?string, B: ?string, C: ?string, H: ?string}
     */
    private function getPokemonAbilitySlots(int $pokemonId): array
    {
        return Cache::remember("p3d.pokemon.{$pokemonId}.ability_slots", now()->addDay(), function () use ($pokemonId): array {
            $defaults = [
                'A' => null,
                'B' => null,
                'C' => null,
                'H' => null,
            ];

            try {
                $response = Http::timeout(5)->get(
                    "https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Pokemon/Data/{$pokemonId}.dat"
                );

                if ($response->failed()) {
                    return $defaults;
                }

                $fields = [];

                foreach (preg_split("/\r\n|\n|\r/", $response->body()) ?: [] as $line) {
                    if (! str_contains($line, '|')) {
                        continue;
                    }

                    [$key, $value] = explode('|', $line, 2);
                    $fields[trim($key)] = trim($value);
                }

                return [
                    'A' => $fields['Ability1'] ?? null,
                    'B' => $fields['Ability2'] ?? null,
                    'C' => $fields['Ability3'] ?? null,
                    'H' => $fields['HiddenAbility'] ?? null,
                ];
            } catch (Exception $e) {
                Log::error($e->getMessage());

                return $defaults;
            }
        });
    }

    public function getPokemonName(string $id): string
    {
        return $this->catalogue->pokemonName($id);
    }

    public function getItemName(string $id): string
    {
        return $this->catalogue->itemName($id);
    }

    public function getAbilityName(string $id): string
    {
        return $this->catalogue->abilityName($id);
    }

    public function getNatureName(string $id): string
    {
        return $this->catalogue->natureName($id);
    }
}
