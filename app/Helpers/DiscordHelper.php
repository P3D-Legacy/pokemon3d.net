<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use RestCord\DiscordClient;

class DiscordHelper
{
    private $discordClient;

    public function __construct()
    {
        $this->discordClient = config('discord.token') && config('discord.server_id') ? new DiscordClient(['token' => config('discord.token')]) : null;
    }

    public function getServer()
    {
        return $this->discordClient->guild->getGuild(['guild.id' => config('discord.server_id'), 'with_counts' => true]);
    }

    public static function countMembers()
    {
        $that = new DiscordHelper;
        try {
            return $that->getServer()->approximate_member_count;
        }
        catch(\Exception $exception) {
            return 0;
        }
    }
}