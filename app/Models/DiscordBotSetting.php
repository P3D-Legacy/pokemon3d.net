<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscordBotSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'chat_id',
        'events_id',
        'hide_events',
    ];
}
