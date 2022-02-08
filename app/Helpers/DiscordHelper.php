<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use RestCord\DiscordClient;

class DiscordHelper
{
    private $discordClient;

    public function __construct()
    {
        $this->discordClient =
            config("discord.token") && config("discord.server_id")
                ? new DiscordClient(["token" => config("discord.token")])
                : null;
    }

    public function getServer()
    {
        return $this->discordClient->guild->getGuild([
            "guild.id" => config("discord.server_id"),
            "with_counts" => true,
        ]);
    }

    public static function countMembers()
    {
        $client = new DiscordHelper();
        try {
            return $client->getServer()->approximate_member_count;
        } catch (\Exception $exception) {
            return 0;
        }
    }

    public static function getServerRoles()
    {
        $client = new DiscordHelper();
        try {
            return $client->discordClient->guild->getGuildRoles([
                "guild.id" => config("discord.server_id"),
            ]);
        } catch (\Exception $exception) {
            return 0;
        }
    }

    public static function getMemberRoles(int $user_id)
    {
        $client = new DiscordHelper();
        try {
            return $client->discordClient->guild->getGuildMember([
                "guild.id" => config("discord.server_id"),
                "user.id" => $user_id,
            ]);
        } catch (\Exception $exception) {
            return 0;
        }
    }

    public static function setMemberRole(int $user_id, int $role_id)
    {
        $client = new DiscordHelper();
        try {
            return $client->discordClient->guild->addGuildMemberRole([
                "guild.id" => config("discord.server_id"),
                "user.id" => $user_id,
                "role.id" => $role_id,
            ]);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
