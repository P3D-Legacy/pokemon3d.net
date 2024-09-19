<?php

namespace App\Helpers;

use RestCord\DiscordClient;

class DiscordHelper
{
    private ?DiscordClient $discordClient;

    private int $guildId;

    public function __construct()
    {
        $this->discordClient =
            config('services.discord.token') && config('services.discord.server_id')
                ? new DiscordClient(['token' => config('services.discord.token')])
                : null;
        $this->guildId = intval(config('services.discord.server_id'));
    }

    public function getServer(): \RestCord\Model\Guild\Guild
    {
        return $this->discordClient->guild->getGuild([
            'guild.id' => $this->guildId,
            'with_counts' => true,
        ]);
    }

    public static function countMembers(): ?int
    {
        $client = new self;
        try {
            return $client->getServer()->approximate_member_count;
        } catch (\Exception $exception) {
            logger()->error($exception->getMessage());

            return 0;
        }
    }

    public static function getServerRoles(): \Exception|array
    {
        $client = new self;
        try {
            return $client->discordClient->guild->getGuildRoles([
                'guild.id' => $client->guildId,
            ]);
        } catch (\Exception $exception) {
            logger()->error($exception->getMessage());

            return $exception;
        }
    }

    public static function getMemberRoles(int $user_id): \RestCord\Model\Guild\GuildMember|\Exception
    {
        $client = new self;
        try {
            return $client->discordClient->guild->getGuildMember([
                'guild.id' => $client->guildId,
                'user.id' => $user_id,
            ]);
        } catch (\Exception $exception) {
            logger()->error($exception->getMessage());

            return $exception;
        }
    }

    public static function setMemberRole(int $user_id, int $role_id): array|string
    {
        $client = new self;
        try {
            return $client->discordClient->guild->addGuildMemberRole([
                'guild.id' => $client->guildId,
                'user.id' => $user_id,
                'role.id' => $role_id,
            ]);
        } catch (\Exception $exception) {
            logger()->error($exception->getMessage());

            return $exception->getMessage();
        }
    }
}
