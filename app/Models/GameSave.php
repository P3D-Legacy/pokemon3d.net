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
        # Explode the player data into an array on each return new line character
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
        $earnedAchievements = explode(',', $earnedAchievements);
        return $earnedAchievements;
    }
}
