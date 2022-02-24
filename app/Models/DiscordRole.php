<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscordRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'color',
        'hoist',
        'id',
        'managed',
        'mentionable',
        'name',
        'permissions',
        'position',
    ];
}
