<?php

namespace App\Console\Commands;

use App\Helpers\DiscordHelper;
use App\Models\DiscordAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DiscordUserRoleSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:syncuserroles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Discord User Roles';

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
     */
    public function handle(): int
    {
        $this->info('Getting Discord roles...');
        Artisan::call('discord:syncroles');
        $accounts = DiscordAccount::all();
        foreach ($accounts as $account) {
            $this->info('Syncing roles for '.$account->username);
            try {
                $roles = DiscordHelper::getMemberRoles($account->id)->roles;
                $account->roles()->sync($roles);
            } catch (\Exception $exception) {
                $this->error('Error syncing roles for '.$account->username);
                $this->error($exception->getMessage());

                continue;
            }
        }
        $this->info('Done!');

        return Command::SUCCESS;
    }
}
