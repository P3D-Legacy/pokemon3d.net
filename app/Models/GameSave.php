<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getPlayerData($key_name = null)
    {
        // Explode the player data into an array on each return new line character
        $playerDataLines = explode("\r\n", $this->player);
        $playerData = [];
        foreach ($playerDataLines as $line) {
            $line = explode('|', $line);
            $playerData[$line[0]] = $line[1];
        }

        return $playerData[$key_name] ?? $playerData;
    }

    public function getAchievements(): array
    {
        $earnedAchievements = $this->getPlayerData('EarnedAchievements');

        return explode(',', $earnedAchievements);
    }

    public function getPokedex(): array
    {
        $pokedex = $this->pokedex;
        $pokedex = explode("\r\n", $pokedex);
        $pokedex = array_filter($pokedex);

        return array_map(function ($item) {
            $item = explode('|', $item);

            return [
                'id' => str_replace('{', '', $item[0]),
                'seen' => $item[1] >= 1,
                'caught' => $item[1] >= 2,
            ];
        }, $pokedex);
    }

    // Get all seen pokemon in pokedex
    public function getSeenPokemon(): array
    {
        $pokedex = $this->getPokedex();

        return array_filter($pokedex, function ($item) {
            return $item['seen'];
        });
    }

    // Get all caught pokemon in pokedex
    public function getCaughtPokemon(): array
    {
        $pokedex = $this->getPokedex();

        return array_filter($pokedex, function ($item) {
            return $item['caught'];
        });
    }

    // Get statistics
    public function getStatistics(): array
    {
        $statistics = $this->statistics;
        $statistics = explode("\r\n", $statistics);
        $statistics = array_filter($statistics);

        return array_map(function ($item) {
            $item = explode(',', $item);
            $name = str_replace('{', '', $item[0]);
            // Remove [ and ] and some random number between from the name
            $name = preg_replace('/\[[0-9]+\]/', '', $name);
            $number = format('locale-number', $item[1]);
            // Remove the last three characters to remove the .00
            $number = substr($number, 0, -3);

            return [
                'name' => $name,
                'value' => $number,
            ];
        }, $statistics);
    }

    // Get pokemon in party
    public function getParty(): array
    {
        $party = $this->party;
        $party = explode("\r\n", $party);
        // Split values for party entry into an array
        $party = array_map(function ($item) {
            return explode('}{', $item);
        }, $party);
        // For each party entry; get the properties and add it to the pokemon in array
        $party = array_map(function ($item) {
            $pokemon = [];
            $private_keys = ['IDValue'];
            foreach ($item as $property) {
                $property = explode('"[', $property);
                $key = str_replace('{', '', str_replace('"', '', $property[0]));
                $value = str_replace(']', '', str_replace('}', '', $property[1]));
                if (in_array($key, $private_keys)) {
                    continue;
                }
                if ($key == 'Experience') {
                    $value = substr(format('locale-number', $value), 0, -3);
                }
                if ($key == 'Nature') {
                    $value = $this->getNature($value);
                }
                if ($key == 'Ability') {
                    $value = $this->getAbility($value);
                }
                if ($key == 'Pokemon') {
                    $pokemon['PokemonName'] = $this->getPokemonName($value);
                }
                $pokemon[$key] = $value;
            }
            $url = $pokemon['EggSteps'] > 0 ? 'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Pokemon/Egg/Egg_front.png' : 'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Pokemon/Sprites/'.$pokemon['Pokemon'].'.png';
            $image = imagecrop(imagecreatefromstring(file_get_contents($url)), [
                'x' => 0,
                'y' => $pokemon['isShiny'] ? 96 : 0,
                'width' => 96,
                'height' => 96,
            ]);
            ob_start(); // Let's start output buffering
            imagejpeg($image); // This will normally output the image, but because of ob_start(), it won't
            $contents = ob_get_contents(); // Instead, output above is saved to $contents
            ob_end_clean(); // End the output buffer
            $pokemon['Image'] = 'data:image/png;base64,'.base64_encode($contents);

            return $pokemon;
        }, $party);

        return $party;
    }

    // Get nature from int
    public function getNature($natureInt): string
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

        return $natures[$natureInt];
    }

    // Get abilities from int
    public function getAbility($abilityInt): string
    {
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

        return $abilities[$abilityInt];
    }

    // Get the pokemon name from id
    public function getPokemonName($id): string
    {
        $filepath = lang_path().'/pokemon_'.app()->getLocale().'.json';
        // if the file doesn't exist, use the default language
        if (!file_exists($filepath)) {
            $filepath = lang_path().'/pokemon_en.json';
        }
        // load pokemon names from json file in the lang folder
        $pokemon_names = json_decode(file_get_contents($filepath), true);
        // get the pokemon name by the id key in json file
        $pokemon_names = collect($pokemon_names);
        return $pokemon_names->get($id-1)['name'];
    }
}
