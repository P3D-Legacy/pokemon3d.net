<?php

namespace App\Console\Commands;

use App\Helpers\DiscordHelper;
use App\Models\DiscordRole;
use Illuminate\Console\Command;

class DiscordRoleSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:syncroles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Discord Roles';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $server_roles = DiscordHelper::getServerRoles();
        foreach ($server_roles as $server_role) {
            if (str_starts_with($server_role->name, '⁣     ^')) {
                $this->info('Skipping role: '.$server_role->name);

                continue;
            }
            $this->info('Syncing role: '.$server_role->name);
            $discord_role = DiscordRole::firstOrNew(['id' => $server_role->id]);
            $discord_role->fill([
                'color' => $server_role->color,
                'hoist' => $server_role->hoist,
                'managed' => $server_role->managed,
                'mentionable' => $server_role->mentionable,
                'name' => $server_role->name,
                'permissions' => $server_role->permissions,
                'position' => $server_role->position,
            ]);
            $discord_role->save();
        }
        $this->info('Discord roles synced.');
    }
}
