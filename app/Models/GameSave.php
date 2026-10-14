<?php

namespace App\Models;

use App\Support\GameDataCatalogue;
use App\Support\GameSaveParser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    private ?GameSaveParser $parser = null;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPlayerData($key_name = null)
    {
        return $this->parser()->getPlayerData($key_name);
    }

    public function getPlayerDataDetails(): array
    {
        return $this->parser()->getPlayerDataDetails();
    }

    public function getAchievements(): array
    {
        return $this->parser()->getAchievements();
    }

    public function getPokedex(): array
    {
        return $this->parser()->getPokedex();
    }

    /**
     * @param  list<string>  $ids
     * @return list<array{id: string, name: string, seen: bool, caught: bool}>
     */
    public function getPokedexByIds(array $ids): array
    {
        return $this->parser()->getPokedexByIds($ids);
    }

    public function getSeenPokemon(): array
    {
        return $this->parser()->getSeenPokemon();
    }

    public function getCaughtPokemon(): array
    {
        return $this->parser()->getCaughtPokemon();
    }

    public function getSeenPokemonCount(): int
    {
        return $this->parser()->getSeenPokemonCount();
    }

    public function getCaughtPokemonCount(): int
    {
        return $this->parser()->getCaughtPokemonCount();
    }

    public function getStatistics(): array
    {
        return $this->parser()->getStatistics();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getParty(): array
    {
        return $this->parser()->getParty();
    }

    /**
     * @return list<array{box_index: int, position: int, pokemon: array<string, mixed>}>
     */
    public function getBox(): array
    {
        return $this->parser()->getBox();
    }

    /**
     * @return list<array{id: string, name: string, amount: int}>
     */
    public function getItems(): array
    {
        return $this->parser()->getItems();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getDaycare(): array
    {
        return $this->parser()->getDaycare();
    }

    /**
     * @return list<array{id: int, name: ?string, play_time: ?string, points: ?string, ot: ?string, skin: ?string, pokemon: list<array<string, mixed>>}>
     */
    public function getHallOfFame(): array
    {
        return $this->parser()->getHallOfFame();
    }

    /**
     * @return list<array{roamer_id: string, pokemon_id: string, level: int, world_id: int, level_file: string, music_loop: string, shiny: bool, pokemon: ?array<string, mixed>}>
     */
    public function getRoamingPokemon(): array
    {
        return $this->parser()->getRoamingPokemon();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getApricorns(): array
    {
        return $this->parser()->getApricorns();
    }

    /**
     * @return list<array{map_path: string, map_name: string, position: ?string, berry_id: string, berry_name: string, berry_count: int, watered_stages: int, timestamp: ?string}>
     */
    public function getBerries(): array
    {
        return $this->parser()->getBerries();
    }

    /**
     * @return list<array{map_path: string, map_name: string, item_id: string, item_name: string}>
     */
    public function getItemData(): array
    {
        return $this->parser()->getItemData();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function parsePokemonCode(string $line): ?array
    {
        return $this->parser()->parsePokemonCode($line);
    }

    public function getNature(mixed $natureInt): string
    {
        return $this->parser()->getNature($natureInt);
    }

    public function getAbility(mixed $ability, ?int $pokemonId = null): string
    {
        return $this->parser()->getAbility($ability, $pokemonId);
    }

    public function resolveAbilityIdFromSlot(?int $pokemonId, string $slot): ?int
    {
        return $this->parser()->resolveAbilityIdFromSlot($pokemonId, $slot);
    }

    public function getPokemonName(string $id): string
    {
        return $this->parser()->getPokemonName($id);
    }

    public function getItemName(string $id): string
    {
        return $this->parser()->getItemName($id);
    }

    public function getAbilityName(string $id): string
    {
        return $this->parser()->getAbilityName($id);
    }

    public function getNatureName(string $id): string
    {
        return $this->parser()->getNatureName($id);
    }

    private function parser(): GameSaveParser
    {
        return $this->parser ??= new GameSaveParser($this, app(GameDataCatalogue::class));
    }
}
