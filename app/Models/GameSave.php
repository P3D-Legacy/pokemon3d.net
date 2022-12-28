<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
