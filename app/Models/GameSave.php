<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GameSave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'apricorns',
        'berries',
        'box',
        'daycare',
        'halloffame',
        'itemdata',
        'items',
        'npc',
        'options',
        'party',
        'player',
        'pokedex',
        'register',
        'roamingpokemon',
        'secretbase',
        'statistics',
    ];

    protected $primaryKey = 'uuid';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });

        self::updating(function ($model) {
            if (! $model->uuid) {
                $model->uuid = Str::uuid()->toString();
            }
        });
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getPlayerData($key_name = null)
    {
        try {
            // Explode the player data into an array on each return new line character
            $playerDataLines = preg_split('/\r\n|\n|\r/', (string) $this->player) ?: [];
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

            return $playerData[$key_name] ?? $playerData;
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return [];
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
                Log::error('User ID: '.$this->user_id);

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
        try {
            $pokedex = $this->pokedex;
            $pokedex = explode("\r\n", $pokedex);
            $pokedex = array_filter($pokedex);

            return array_map(function ($item) {
                $item = explode('|', $item);
                $id = str_replace(['{', '}'], '', $item[0]);
                $status = (int) $item[1];

                return [
                    'id' => $id,
                    'name' => $this->getPokemonName($id),
                    'seen' => $status >= 1,
                    'caught' => $status >= 2,
                ];
            }, $pokedex);
        } catch (Exception $e) {
            // If there is an error, return an empty array and log the error
            Log::error($e->getMessage());

            return [];
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
                    'name' => $this->getPokemonName($id),
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
            $statistics = $this->statistics;
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
            $lines = $this->splitSaveLines((string) $this->party);

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

            foreach ($this->splitSaveLines((string) $this->box) as $line) {
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

            foreach ($this->splitSaveLines((string) $this->items) as $line) {
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
                    'name' => $this->getItemName((string) $itemId),
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

            foreach ($this->splitSaveLines((string) $this->daycare) as $line) {
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
                        'name' => $this->getPokemonName((string) $numericId),
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

            foreach ($this->splitSaveLines((string) $this->halloffame) as $line) {
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

            foreach ($this->splitSaveLines((string) $this->roamingpokemon) as $line) {
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

            foreach ($this->splitSaveLines((string) $this->apricorns) as $line) {
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
     * @return list<array{map_path: string, position: ?string, berry_id: string, berry_name: string, berry_count: int, watered_stages: int, timestamp: ?string}>
     */
    public function getBerries(): array
    {
        try {
            $entries = [];

            foreach ($this->splitSaveLines((string) $this->berries) as $line) {
                if (! str_starts_with($line, '{') || ! str_ends_with($line, '}')) {
                    continue;
                }

                $inner = substr($line, 1, -1);
                $parts = explode('|', $inner);

                if (count($parts) < 3) {
                    continue;
                }

                $berryParts = array_pad(explode(',', $parts[2]), 3, '0');
                $berryId = (string) $berryParts[0];

                $entries[] = [
                    'map_path' => $parts[0],
                    'position' => $parts[1] ?? null,
                    'berry_id' => $berryId,
                    'berry_name' => $this->getItemName($berryId),
                    'berry_count' => (int) $berryParts[1],
                    'watered_stages' => (int) $berryParts[2],
                    'timestamp' => $parts[3] ?? null,
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
     * @return list<array{map_path: string, item_id: string, item_name: string}>
     */
    public function getItemData(): array
    {
        try {
            $raw = trim((string) $this->itemdata);

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
                    'item_id' => $itemId,
                    'item_name' => $this->getItemName($itemId),
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
            'name' => $this->getPokemonName((string) $pokemonId),
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
        return $this->getNatureName((string) $natureInt) ?? '???';
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

        return $this->getAbilityName((string) $ability) ?? '???';
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

    // Get the pokemon name from id
    public function getPokemonName(string $id): string
    {
        try {
            $filepath = lang_path().'/pokemon_'.app()->getLocale().'.json';

            if (! file_exists($filepath)) {
                $filepath = lang_path().'/pokemon_en.json';
            }

            $pokemonNames = collect(json_decode(file_get_contents($filepath), true));
            $match = $pokemonNames->firstWhere('id', $id);

            if (! is_array($match)) {
                return '???';
            }

            return $match['name'] ?? '???';
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return '???';
        }
    }

    public function getItemName(string $id): string
    {
        try {
            if ($id === '' || $id === '0') {
                return __('None');
            }

            $filepath = lang_path().'/items_'.app()->getLocale().'.json';

            if (! file_exists($filepath)) {
                $filepath = lang_path().'/items_en.json';
            }

            if (! file_exists($filepath)) {
                return "Item #{$id}";
            }

            $itemNames = collect(json_decode((string) file_get_contents($filepath), true));
            $match = $itemNames->firstWhere('id', $id);

            if (! is_array($match)) {
                return "Item #{$id}";
            }

            return $match['name'] ?? "Item #{$id}";
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return "Item #{$id}";
        }
    }

    public function getAbilityName(string $id): string
    {
        try {
            if ($id === '') {
                return __('None');
            }

            $filepath = lang_path().'/abilities_'.app()->getLocale().'.json';

            if (! file_exists($filepath)) {
                $filepath = lang_path().'/abilities_en.json';
            }

            if (! file_exists($filepath)) {
                return '???';
            }

            $abilityNames = collect(json_decode((string) file_get_contents($filepath), true));
            $match = $abilityNames->firstWhere('id', $id);

            if (! is_array($match)) {
                return '???';
            }

            return $match['name'] ?? '???';
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return '???';
        }
    }

    public function getNatureName(string $id): string
    {
        try {
            if ($id === '') {
                return __('None');
            }

            $filepath = lang_path().'/natures_'.app()->getLocale().'.json';

            if (! file_exists($filepath)) {
                $filepath = lang_path().'/natures_en.json';
            }

            if (! file_exists($filepath)) {
                return "Nature #{$id}";
            }

            $natureNames = collect(json_decode((string) file_get_contents($filepath), true));
            $match = $natureNames->firstWhere('id', $id);

            if (! is_array($match)) {
                return "Nature #{$id}";
            }

            return $match['name'] ?? "Nature #{$id}";
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return "Nature #{$id}";
        }
    }
}
