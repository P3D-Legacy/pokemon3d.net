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
            'nature' => isset($raw['Nature']) ? $this->getNature($raw['Nature']) : null,
            'ability' => isset($raw['Ability']) ? $this->getAbility($raw['Ability'], $pokemonId) : null,
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
        $natures = [
            0 => 'Hardy',
            1 => 'Lonely',
            2 => 'Brave',
            3 => 'Adamant',
            4 => 'Naughty',
            5 => 'Bold',
            6 => 'Docile',
            7 => 'Relaxed',
            8 => 'Impish',
            9 => 'Lax',
            10 => 'Timid',
            11 => 'Hasty',
            12 => 'Serious',
            13 => 'Jolly',
            14 => 'Naive',
            15 => 'Modest',
            16 => 'Mild',
            17 => 'Quiet',
            18 => 'Bashful',
            19 => 'Rash',
            20 => 'Calm',
            21 => 'Gentle',
            22 => 'Sassy',
            23 => 'Careful',
            24 => 'Quirky',
        ];

        return $natures[(int) $natureInt] ?? '???';
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

        $abilities = [
            0 => 'None',
            1 => 'Stench',
            2 => 'Drizzle',
            3 => 'Speed Boost',
            4 => 'Battle Armor',
            5 => 'Sturdy',
            6 => 'Damp',
            7 => 'Limber',
            8 => 'Sand Veil',
            9 => 'Static',
            10 => 'Volt Absorb',
            11 => 'Water Absorb',
            12 => 'Oblivious',
            13 => 'Cloud Nine',
            14 => 'Compound Eyes',
            15 => 'Insomnia',
            16 => 'Color Change',
            17 => 'Immunity',
            18 => 'Flash Fire',
            19 => 'Shield Dust',
            20 => 'Own Tempo',
            21 => 'Suction Cups',
            22 => 'Intimidate',
            23 => 'Shadow Tag',
            24 => 'Rough Skin',
            25 => 'Wonder Guard',
            26 => 'Levitate',
            27 => 'Effect Spore',
            28 => 'Synchronize',
            29 => 'Clear Body',
            30 => 'Natural Cure',
            31 => 'Lightning Rod',
            32 => 'Serene Grace',
            33 => 'Swift Swim',
            34 => 'Chlorophyll',
            35 => 'Illuminate',
            36 => 'Trace',
            37 => 'Huge Power',
            38 => 'Poison Point',
            39 => 'Inner Focus',
            40 => 'Magma Armor',
            41 => 'Water Veil',
            42 => 'Magnet Pull',
            43 => 'Soundproof',
            44 => 'Rain Dish',
            45 => 'Sand Stream',
            46 => 'Pressure',
            47 => 'Thick Fat',
            48 => 'Early Bird',
            49 => 'Flame Body',
            50 => 'Run Away',
            51 => 'Keen Eye',
            52 => 'Hyper Cutter',
            53 => 'Pickup',
            54 => 'Truant',
            55 => 'Hustle',
            56 => 'Cute Charm',
            57 => 'Plus',
            58 => 'Minus',
            59 => 'Forecast',
            60 => 'Sticky Hold',
            61 => 'Shed Skin',
            62 => 'Guts',
            63 => 'Marvel Scale',
            64 => 'Liquid Ooze',
            65 => 'Overgrow',
            66 => 'Blaze',
            67 => 'Torrent',
            68 => 'Swarm',
            69 => 'Rock Head',
            70 => 'Drought',
            71 => 'Arena Trap',
            72 => 'Vital Spirit',
            73 => 'White Smoke',
            74 => 'Pure Power',
            75 => 'Shell Armor',
            76 => 'Air Lock',
            77 => 'Tangled Feet',
            78 => 'Motor Drive',
            79 => 'Rivalry',
            80 => 'Steadfast',
            81 => 'Snow Cloak',
            82 => 'Gluttony',
            83 => 'Anger Point',
            84 => 'Unburden',
            85 => 'Heatproof',
            86 => 'Simple',
            87 => 'Dry Skin',
            88 => 'Download',
            89 => 'Iron Fist',
            90 => 'Poison Heal',
            91 => 'Adaptability',
            92 => 'Skill Link',
            93 => 'Hydration',
            94 => 'Solar Power',
            95 => 'Quick Feet',
            96 => 'Normalize',
            97 => 'Sniper',
            98 => 'Magic Guard',
            99 => 'No Guard',
            100 => 'Stall',
            101 => 'Technician',
            102 => 'Leaf Guard',
            103 => 'Klutz',
            104 => 'Mold Breaker',
            105 => 'Super Luck',
            106 => 'Aftermath',
            107 => 'Anticipation',
            108 => 'Forewarn',
            109 => 'Unaware',
            110 => 'Tinted Lens',
            111 => 'Filter',
            112 => 'Slow Start',
            113 => 'Scrappy',
            114 => 'Storm Drain',
            115 => 'Ice Body',
            116 => 'Solid Rock',
            117 => 'Snow Warning',
            118 => 'Honey Gather',
            119 => 'Frisk',
            120 => 'Reckless',
            121 => 'Multitype',
            122 => 'Flower Gift',
            123 => 'Bad Dreams',
            124 => 'Pickpocket',
            125 => 'Sheer Force',
            126 => 'Contrary',
            127 => 'Unnerve',
            128 => 'Defiant',
            129 => 'Defeatist',
            130 => 'Cursed Body',
            131 => 'Healer',
            132 => 'Friend Guard',
            133 => 'Weak Armor',
            134 => 'Heavy Metal',
            135 => 'Light Metal',
            136 => 'Multiscale',
            137 => 'Toxic Boost',
            138 => 'Flare Boost',
            139 => 'Harvest',
            140 => 'Telepathy',
            141 => 'Moody',
            142 => 'Overcoat',
            143 => 'Poison Touch',
            144 => 'Regenerator',
            145 => 'Big Pecks',
            146 => 'Sand Rush',
            147 => 'Wonder Skin',
            148 => 'Analytic',
            149 => 'Illusion',
            150 => 'Imposter',
            151 => 'Infiltrator',
            152 => 'Mummy',
            153 => 'Moxie',
            154 => 'Justified',
            155 => 'Rattled',
            156 => 'Magic Bounce',
            157 => 'Sap Sipper',
            158 => 'Prankster',
            159 => 'Sand Force',
            160 => 'Iron Barbs',
            161 => 'Zen Mode',
            162 => 'Victory Star',
            163 => 'Turboblaze',
            164 => 'Teravolt',
            165 => 'Aroma Veil',
            166 => 'Flower Veil',
            167 => 'Cheek Pouch',
            168 => 'Protean',
            169 => 'Fur Coat',
            170 => 'Magician',
            171 => 'Bulletproof',
            172 => 'Competitive',
            173 => 'Strong Jaw',
            174 => 'Refrigerate',
            175 => 'Sweet Veil',
            176 => 'Stance Change',
            177 => 'Gale Wings',
            178 => 'Mega Launcher',
            179 => 'Grass Pelt',
            180 => 'Symbiosis',
            181 => 'Tough Claws',
            182 => 'Pixilate',
            183 => 'Gooey',
            184 => 'Dark Aura',
            185 => 'Fairy Aura',
            186 => 'Aura Break',
            187 => 'Primordial Sea',
            188 => 'Desolate Land',
        ];

        return $abilities[(int) $ability] ?? '???';
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
}
